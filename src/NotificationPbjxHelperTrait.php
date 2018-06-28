<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\Ncr;
use Gdbots\Pbj\MessageResolver;
use Gdbots\Pbj\SchemaCurie;
use Gdbots\Pbj\SchemaQName;
use Gdbots\Schemas\Ncr\Mixin\Node\Node;
use Gdbots\Schemas\Ncr\Mixin\Publishable\Publishable;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Command\Command;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Triniti\Notify\Exception\InvalidNotificationContent;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;

trait NotificationPbjxHelperTrait
{
    /** @var Ncr */
    protected $ncr;

    /**
     * @param Node $node
     *
     * @return bool
     */
    protected function isNodeSupported(Node $node): bool
    {
        return $node instanceof Notification;
    }

    /**
     * When a notification is created/updated we must ensure the app
     * it is bound to supports this type of notification. In all cases
     * so far this is a one to one, e.g. alexa-notification can only
     * be sent by an alex-app. By convention both apps and notifications
     * are named (the node type) using those matching suffixes.
     *
     * @param SchemaQName $qname
     * @param NodeRef     $appRef
     *
     * @return bool
     */
    protected function isSupportedByApp(SchemaQName $qname, NodeRef $appRef): bool
    {
        $expected = str_replace('-notification', '-app', $qname->toString());
        return $appRef->getQName()->toString() === $expected;
    }

    /**
     * @param Command $command
     * @param string  $suffix
     *
     * @return Event
     */
    protected function createEventFromCommand(Command $command, string $suffix): Event
    {
        $curie = $command::schema()->getCurie();
        $eventCurie = "{$curie->getVendor()}:{$curie->getPackage()}:event:notification-{$suffix}";
        /** @var Event $class */
        $class = MessageResolver::resolveCurie(SchemaCurie::fromString($eventCurie));
        return $class::create();
    }

    /**
     * @param Notification $notification
     *
     * @throws InvalidNotificationContent
     */
    protected function applySchedule(Notification $notification): void
    {
        if ($this->alreadySent($notification)) {
            // schedule cannot change at this point.
            return;
        }

        if ($notification->has('content_ref') && $notification->get('send_on_publish')) {
            /** @var NodeRef $contentRef */
            $contentRef = $notification->get('content_ref');
            $content = $this->ncr->getNode($contentRef, false, $this->createNcrContext($notification));

            if (!$content instanceof HasNotifications || !$content instanceof Publishable) {
                throw new InvalidNotificationContent();
            }

            $notification->set('title', $content->get('title'));
            $notification->set('send_at', $content->get('published_at'));
        } else {
            $notification->set('send_on_publish', false);
        }

        if ($notification->has('send_at')) {
            $notification->set('send_status', NotificationSendStatus::SCHEDULED());
        } else {
            $notification->set('send_status', NotificationSendStatus::DRAFT());
        }
    }

    /**
     * @param Notification $notification
     *
     * @return bool
     */
    protected function alreadySent(Notification $notification): bool
    {
        /** @var NotificationSendStatus $status */
        $status = $notification->get('send_status', NotificationSendStatus::DRAFT());

        if ($status->equals(NotificationSendStatus::SENT())
            || $status->equals(NotificationSendStatus::FAILED())
            || $status->equals(NotificationSendStatus::CANCELED())
        ) {
            return true;
        }

        return false;
    }
}
