<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Common\Util\ClassUtils;
use Gdbots\Ncr\Ncr;
use Gdbots\Ncr\NcrSearch;
use Gdbots\Pbj\MessageResolver;
use Gdbots\Pbj\Schema;
use Gdbots\Pbj\SchemaCurie;
use Gdbots\Pbjx\EventSubscriber;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Ncr\Mixin\DeleteNode\DeleteNode;
use Gdbots\Schemas\Ncr\Mixin\Node\Node;
use Gdbots\Schemas\Ncr\Mixin\NodeDeleted\NodeDeleted;
use Gdbots\Schemas\Ncr\Mixin\NodeExpired\NodeExpired;
use Gdbots\Schemas\Ncr\Mixin\NodeScheduled\NodeScheduled;
use Gdbots\Schemas\Ncr\Mixin\NodeUnpublished\NodeUnpublished;
use Gdbots\Schemas\Ncr\Mixin\NodeUpdated\NodeUpdated;
use Gdbots\Schemas\Ncr\Mixin\UpdateNode\UpdateNode;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Command\Command;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Psr\Log\LoggerInterface;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Enum\SearchNotificationsSort;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotificationsV1Mixin;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\SearchNotificationsRequest\SearchNotificationsRequest;
use Triniti\Schemas\Notify\Mixin\SearchNotificationsRequest\SearchNotificationsRequestV1Mixin;

/**
 * Responsible for watching changes to nodes that have
 * the mixin "triniti:notify:mixin:has-notifications" and
 * keeping their associated notifications up to date.
 */
class HasNotificationsWatcher implements EventSubscriber
{
    /** @var Ncr */
    protected $ncr;

    /** @var NcrSearch */
    protected $ncrSearch;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * @param Ncr             $ncr
     * @param NcrSearch       $ncrSearch
     * @param LoggerInterface $logger
     */
    public function __construct(Ncr $ncr, NcrSearch $ncrSearch, LoggerInterface $logger)
    {
        $this->ncr = $ncr;
        $this->ncrSearch = $ncrSearch;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            'gdbots:ncr:mixin:node-deleted'     => 'onNodeDeleted',
            'gdbots:ncr:mixin:node-expired'     => 'onNodeExpired',
            'gdbots:ncr:mixin:node-scheduled'   => 'onNodeScheduled',
            'gdbots:ncr:mixin:node-updated'     => 'onNodeUpdated',
            'gdbots:ncr:mixin:node-unpublished' => 'onNodeUnpublished',
        ];
    }

    /**
     * @param NodeDeleted $event
     * @param Pbjx        $pbjx
     */
    public function onNodeDeleted(NodeDeleted $event, Pbjx $pbjx): void
    {
        $this->cancelNotification($event, $pbjx);
    }

    /**
     * @param NodeExpired $event
     * @param Pbjx        $pbjx
     */
    public function onNodeExpired(NodeExpired $event, Pbjx $pbjx): void
    {
        $this->cancelNotification($event, $pbjx);
    }

    /**
     * @param NodeScheduled $event
     * @param Pbjx          $pbjx
     */
    public function onNodeScheduled(NodeScheduled $event, Pbjx $pbjx): void
    {
        /** @var NodeRef $contentRef */
        $contentRef = $event->get('node_ref');
        $this->scheduleNotification($event, $pbjx, $contentRef, $event->get('publish_at'));
    }

    /**
     * @param NodeUpdated $event
     * @param Pbjx        $pbjx
     */
    public function onNodeUpdated(NodeUpdated $event, Pbjx $pbjx): void
    {
        /** @var HasNotifications $content */
        $content = $event->get('new_node');
        if (null === $content || !$this->isNodeSupported($content)) {
            return;
        }

        $this->scheduleNotification(
            $event,
            $pbjx,
            NodeRef::fromNode($content),
            $content->get('published_at'),
            $content->get('title')
        );
    }

    /**
     * @param NodeUnpublished $event
     * @param Pbjx            $pbjx
     */
    public function onNodeUnpublished(NodeUnpublished $event, Pbjx $pbjx): void
    {
        $this->cancelNotification($event, $pbjx);
    }

    /**
     * @param Node $node
     *
     * @return bool
     */
    protected function isNodeSupported(Node $node): bool
    {
        return $node instanceof HasNotifications;
    }

    /**
     * @param NodeRef $nodeRef
     *
     * @return bool
     */
    protected function isNodeRefSupported(NodeRef $nodeRef): bool
    {
        static $validQNames = null;

        if (null === $validQNames) {
            /** @var Schema $schema */
            foreach (HasNotificationsV1Mixin::findAll() as $schema) {
                $validQNames[$schema->getQName()->getMessage()] = true;
            }
        }

        return isset($validQNames[$nodeRef->getQName()->toString()]);
    }

    /**
     * @param Event $event
     * @param Pbjx  $pbjx
     */
    protected function cancelNotification(Event $event, Pbjx $pbjx): void
    {
        if ($event->isReplay()) {
            return;
        }

        /** @var NodeRef $contentRef */
        $contentRef = $event->get('node_ref');
        if (null === $contentRef || !$this->isNodeRefSupported($contentRef)) {
            return;
        }

        $request = $this->createSearchNotificationsRequest($event, $pbjx)->set('content_ref', $contentRef);
        $this->forEachNotification($request, $pbjx, function (Notification $node) use ($event, $pbjx) {
            return $this->createDeleteNotification($node, $event, $pbjx);
        });
    }

    /**
     * @param Event     $event
     * @param Pbjx      $pbjx
     * @param NodeRef   $contentRef
     * @param \DateTime $sendAt
     * @param string    $title
     */
    public function scheduleNotification(
        Event $event,
        Pbjx $pbjx,
        NodeRef $contentRef,
        ?\DateTime $sendAt = null,
        ?string $title = null
    ): void {
        if (!$this->isNodeRefSupported($contentRef)) {
            return;
        }

        $request = $this->createSearchNotificationsRequest($event, $pbjx);
        $request
            ->set('content_ref', $contentRef)
            ->set('q', $request->get('q') . ' +send_on_publish:true');

        $this->forEachNotification($request, $pbjx, function (Notification $node)
        use ($sendAt, $title, $event, $pbjx) {
            $command = $this->createUpdateNotification($node, $event, $pbjx);
            $node->set('send_at', $sendAt);
            if ($node->has('send_at')) {
                $node->set('send_status', NotificationSendStatus::SCHEDULED());
            } else {
                $node->set('send_status', NotificationSendStatus::DRAFT());
            }

            if (null !== $title) {
                $node->set('title', $title);
            }

            return $node->equals($command->get('old_node')) ? null : $command;
        });
    }

    /**
     * Finds all notifications for the given request and executes the
     * factory with the node to create a command. The command is then
     * sent via pbjx unless it is null, then it is ignored.
     *
     * @param SearchNotificationsRequest $request
     * @param Pbjx                       $pbjx
     * @param callable                   $factory
     */
    public function forEachNotification(SearchNotificationsRequest $request, Pbjx $pbjx, callable $factory): void
    {
        $lastDate = new \DateTime('100 years ago');

        do {
            $response = $pbjx->request($request);

            /** @var Notification $node */
            foreach ($response->get('nodes', []) as $node) {
                $lastDate = $node->get('created_at')->toDateTime();
                /** @var Command $command */
                $command = $factory($node);

                if (null === $command) {
                    continue;
                }

                try {
                    $pbjx->send($command);
                } catch (\Throwable $e) {
                    $this->logger->error(
                        sprintf('%s [{pbj_schema}] failed to send.', ClassUtils::getShortName($e)),
                        [
                            'exception'  => $e,
                            'pbj_schema' => $command::schema()->getId()->toString(),
                            'pbj'        => $command->toArray(),
                        ]
                    );
                }
            }

            $request = clone $request;
            $request->set('created_after', $lastDate);
        } while ($response->get('has_more'));
    }

    /**
     * @param Event $event
     * @param Pbjx  $pbjx
     *
     * @return SearchNotificationsRequest
     */
    protected function createSearchNotificationsRequest(Event $event, Pbjx $pbjx): SearchNotificationsRequest
    {
        /** @var SearchNotificationsRequest $request */
        $request = SearchNotificationsRequestV1Mixin::findOne()->createMessage();
        $pbjx->copyContext($event, $request);
        return $request
            ->set('q', sprintf(
                '+send_status:(%s OR %s)',
                NotificationSendStatus::DRAFT,
                NotificationSendStatus::SCHEDULED
            ))
            ->set('sort', SearchNotificationsSort::CREATED_AT_ASC())
            ->set('count', 255);
    }

    /**
     * @param Notification $notification
     * @param Event        $event
     * @param Pbjx         $pbjx
     *
     * @return DeleteNode
     */
    protected function createDeleteNotification(Notification $notification, Event $event, Pbjx $pbjx): DeleteNode
    {
        $curie = $notification::schema()->getCurie();
        /** @var DeleteNode $class */
        $class = MessageResolver::resolveCurie(
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:command:delete-notification")
        );

        $command = $class::create()->set('node_ref', NodeRef::fromNode($notification));
        $pbjx->copyContext($event, $command);
        return $command;
    }

    /**
     * @param Notification $notification
     * @param Event        $event
     * @param Pbjx         $pbjx
     *
     * @return UpdateNode
     */
    protected function createUpdateNotification(Notification $notification, Event $event, Pbjx $pbjx): UpdateNode
    {
        $curie = $notification::schema()->getCurie();
        /** @var UpdateNode $class */
        $class = MessageResolver::resolveCurie(
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:command:update-notification")
        );

        $command = $class::create()
            ->set('node_ref', NodeRef::fromNode($notification))
            ->set('old_node', (clone $notification)->freeze())
            ->set('new_node', $notification);
        $pbjx->copyContext($event, $command);
        return $command;
    }
}
