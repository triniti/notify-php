<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\NcrProjector;
use Gdbots\Pbj\Message;
use Gdbots\Pbj\MessageResolver;
use Gdbots\Pbjx\Pbjx;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

class NcrNotificationProjector extends NcrProjector
{
    // todo: i think...we dont need this? because the handler will do it in aggregate?
    public static function getSubscribedEvents()
    {
        $vendor = MessageResolver::getDefaultVendor();
        return [
            "{$vendor}:notify:event:notification-failed" => 'onNotificationFailed',
            "{$vendor}:notify:event:notification-sent"   => 'onNotificationSent',
        ];
    }

    public function onNotificationFailed(Message $event, Pbjx $pbjx): void
    {
        $aggregate = NotificationAggregate::fromNodeRef($event->get('node_ref'), $pbjx);
        $aggregate->sync();
        $node = $aggregate->getNode();
        $node
            ->set('send_status', NotificationSendStatus::FAILED())
            ->clear('sent_at')
            ->set('notifier_result', $event->get('notifier_result'));
        $this->projectNode($node, $event, $pbjx);
    }

    public function onNotificationSent(Message $event, Pbjx $pbjx): void
    {
        $aggregate = NotificationAggregate::fromNodeRef($event->get('node_ref'), $pbjx);
        $aggregate->sync();
        $node = $aggregate->getNode();
        $node
            ->set('send_status', NotificationSendStatus::SENT())
            ->set('sent_at', $event->get('occurred_at')->toDateTime())
            ->set('notifier_result', $event->get('notifier_result'));
        $this->projectNode($node, $event, $pbjx);
    }
}
