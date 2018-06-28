<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractNodeProjector;
use Gdbots\Pbj\Schema;
use Gdbots\Pbjx\EventSubscriber;
use Gdbots\Pbjx\EventSubscriberTrait;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\Mixin\Node\Node;
use Gdbots\Schemas\Ncr\Mixin\NodeCreated\NodeCreated;
use Gdbots\Schemas\Ncr\Mixin\NodeDeleted\NodeDeleted;
use Gdbots\Schemas\Ncr\Mixin\NodeUpdated\NodeUpdated;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;
use Triniti\Schemas\Notify\Mixin\NotificationFailed\NotificationFailed;
use Triniti\Schemas\Notify\Mixin\NotificationSent\NotificationSent;
use Triniti\Schemas\Notify\Mixin\SendNotification\SendNotification;
use Triniti\Schemas\Notify\Mixin\SendNotification\SendNotificationV1Mixin;

class NcrNotificationProjector extends AbstractNodeProjector implements EventSubscriber
{
    use EventSubscriberTrait;

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        /** @var Schema $schema */
        $schema = NotificationV1Mixin::findAll()[0];
        $curie = $schema->getCurie();
        return [
            "{$curie->getVendor()}:{$curie->getPackage()}:event:*" => 'onEvent',
        ];
    }

    /**
     * @param NodeCreated $event
     * @param Pbjx        $pbjx
     */
    public function onNotificationCreated(NodeCreated $event, Pbjx $pbjx): void
    {
        $this->handleNodeCreated($event, $pbjx);
        if ($event->isReplay()) {
            return;
        }

        $this->createSendNotificationJob($event->get('node'), $event, $pbjx);
    }

    /**
     * @param NodeDeleted $event
     * @param Pbjx        $pbjx
     */
    public function onNotificationDeleted(NodeDeleted $event, Pbjx $pbjx): void
    {
        $this->handleNodeDeleted($event, $pbjx);
        if ($event->isReplay()) {
            return;
        }

        /** @var NodeRef $nodeRef */
        $nodeRef = $event->get('node_ref');
        $pbjx->cancelJobs(["{$nodeRef}.send"]);
    }

    /**
     * @param NotificationFailed $event
     * @param Pbjx               $pbjx
     */
    public function onNotificationFailed(NotificationFailed $event, Pbjx $pbjx): void
    {
        $node = $this->ncr->getNode($event->get('node_ref'), true, $this->createNcrContext($event));
        $node->set('send_status', NotificationSendStatus::FAILED())
            ->clear('sent_at')
            ->set('notifier_result', $event->get('notifier_result'));
        $this->updateAndIndexNode($node, $event, $pbjx);
    }

    /**
     * @param NotificationSent $event
     * @param Pbjx             $pbjx
     */
    public function onNotificationSent(NotificationSent $event, Pbjx $pbjx): void
    {
        $node = $this->ncr->getNode($event->get('node_ref'), true, $this->createNcrContext($event));
        $node->set('send_status', NotificationSendStatus::SENT())
            ->set('sent_at', $event->get('occurred_at')->toDateTime())
            ->set('notifier_result', $event->get('notifier_result'));
        $this->updateAndIndexNode($node, $event, $pbjx);
    }

    /**
     * @param NodeUpdated $event
     * @param Pbjx        $pbjx
     */
    public function onNotificationUpdated(NodeUpdated $event, Pbjx $pbjx): void
    {
        $this->handleNodeUpdated($event, $pbjx);
        if ($event->isReplay()) {
            return;
        }

        /** @var Notification $oldNode */
        $oldNode = $event->get('old_node');

        /** @var Notification $newNode */
        $newNode = $event->get('new_node');

        $this->cancelOrCreateSendNotificationJob($newNode, $event, $pbjx, $oldNode);
    }

    /**
     * @param Node  $node
     * @param Event $event
     * @param Pbjx  $pbjx
     *
     * @return SendNotification
     */
    protected function createSendNotification(Node $node, Event $event, Pbjx $pbjx): SendNotification
    {
        /** @var SendNotification $command */
        $command = SendNotificationV1Mixin::findOne()->createMessage();
        return $command;
    }

    /**
     * @param Notification $node
     * @param Event        $event
     * @param Pbjx         $pbjx
     */
    protected function createSendNotificationJob(Notification $node, Event $event, Pbjx $pbjx): void
    {
        if (!$node->has('send_at') || !$node->has('send_status')) {
            return;
        }

        if (!$node->get('send_status')->equals(NotificationSendStatus::SCHEDULED())) {
            // only a scheduled notification should create a job
            return;
        }

        /** @var NodeRef $nodeRef */
        $nodeRef = $event->get('node_ref') ?: NodeRef::fromNode($node);

        /** @var \DateTime $sendAt */
        $sendAt = $node->get('send_at');

        $command = $this->createSendNotification($node, $event, $pbjx)->set('node_ref', $nodeRef);
        $pbjx->copyContext($event, $command);

        if ($sendAt->getTimestamp() <= (time() + 5)) {
            $pbjx->send($command);
            return;
        }

        $pbjx->sendAt($command, $sendAt->getTimestamp(), "{$nodeRef}.send");
    }

    /**
     * @param Notification $newNode
     * @param Event        $event
     * @param Pbjx         $pbjx
     * @param Notification $oldNode
     */
    protected function cancelOrCreateSendNotificationJob(
        Notification $newNode,
        Event $event,
        Pbjx $pbjx,
        ?Notification $oldNode = null
    ): void {
        /** @var NodeRef $nodeRef */
        $nodeRef = $event->get('node_ref') ?: NodeRef::fromNode($newNode);
        $sendAtField = $newNode::schema()->getField('send_at');

        /** @var \DateTime $oldSendAt */
        $oldSendAt = $oldNode ? $oldNode->get('send_at') : null;

        $oldSendAt = $sendAtField->getType()->encode($oldSendAt, $sendAtField);
        $newSendAt = $sendAtField->getType()->encode($newNode->get('send_at'), $sendAtField);

        if ($oldSendAt === $newSendAt) {
            return;
        }

        if (null === $newSendAt) {
            if (null !== $oldSendAt) {
                $pbjx->cancelJobs(["{$nodeRef}.send"]);
            }
            return;
        }

        $this->createSendNotificationJob($newNode, $event, $pbjx);
    }

    /**
     * @param Node  $node
     * @param Event $event
     * @param Pbjx  $pbjx
     */
    protected function updateAndIndexNode(Node $node, Event $event, Pbjx $pbjx): void
    {
        /** @var NodeStatus $status */
        $status = $node->get('status');

        /** @var NotificationSendStatus $sendStatus */
        $sendStatus = $node->get('send_status', NotificationSendStatus::UNKNOWN());

        if ($status->equals(NodeStatus::DELETED())
            && !$sendStatus->equals(NotificationSendStatus::SENT())
            && !$sendStatus->equals(NotificationSendStatus::FAILED())
        ) {
            $node->set('send_status', NotificationSendStatus::CANCELED());
        }

        parent::updateAndIndexNode($node, $event, $pbjx);
    }
}
