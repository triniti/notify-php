<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier;

use Gdbots\Pbj\Message;
use Gdbots\Schemas\Ncr\NodeRef;

class FcmIosNotifier extends AbstractFcmNotifier
{
    const DISABLED_FLAG_NAME = 'fcm_ios_notifier_disabled';

    /**
     * @link https://developer.apple.com/library/archive/documentation/NetworkingInternet/Conceptual/RemoteNotificationsPG/CreatingtheNotificationPayload.html
     *
     * {@inheritdoc}
     */
    protected function buildPayload(Message $notification, Message $app, ?Message $content = null): array
    {
        $payload = parent::buildPayload($notification, $app, $content);
        $payload['aps'] = [
            'alert'           => $payload['notification']['title'],
            'category'        => 'COMMENT_SNOOZE',
            'mutable-content' => 1,
        ];
        $payload['notification_ref'] = NodeRef::fromNode($notification)->toString();

        if (null !== $content) {
            $contentRef = NodeRef::fromNode($content);
            $payload['node_ref'] = $contentRef->toString();
            // ones below are for legacy apps
            $payload['type'] = "{$contentRef->getVendor()}#{$contentRef->getLabel()}";
            $payload['guid'] = $contentRef->getId();
        }

        return $payload;
    }
}
