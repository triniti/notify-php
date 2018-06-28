<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractUpdateNodeHandler;
use Gdbots\Pbj\Schema;
use Gdbots\Pbj\SchemaCurie;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\Mixin\NodeUpdated\NodeUpdated;
use Gdbots\Schemas\Ncr\Mixin\UpdateNode\UpdateNode;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;

class UpdateNotificationHandler extends AbstractUpdateNodeHandler
{
    use NotificationPbjxHelperTrait;

    /**
     * {@inheritdoc}
     */
    protected function beforePutEvents(NodeUpdated $event, UpdateNode $command, Pbjx $pbjx): void
    {
        parent::beforePutEvents($event, $command, $pbjx);

        /** @var Notification $oldNode */
        $oldNode = $event->get('old_node');

        /** @var Notification $newNode */
        $newNode = $event->get('new_node');

        $newNode
            // app_ref SHOULD NOT change during an update
            ->set('app_ref', $oldNode->get('app_ref'))
            // content_ref SHOULD NOT change during an update
            ->set('content_ref', $oldNode->get('content_ref'))
            // send_status SHOULD NOT change during an update by
            // a user action, it MAY change when scheduling is applied
            ->set('send_status', $oldNode->get('send_status'))
            // sent_at SHOULD NOT change during an update,
            // it is updated when notifications are sent
            ->set('sent_at', $oldNode->get('sent_at'));

        // notifications are only published or deleted, enforce it.
        if (!NodeStatus::DELETED()->equals($newNode->get('status'))) {
            $newNode->set('status', NodeStatus::PUBLISHED());
        }

        $this->applySchedule($newNode);
    }

    /**
     * {@inheritdoc}
     */
    public static function handlesCuries(): array
    {
        /** @var Schema $schema */
        $schema = NotificationV1Mixin::findAll()[0];
        $curie = $schema->getCurie();
        return [
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:command:update-notification"),
        ];
    }
}
