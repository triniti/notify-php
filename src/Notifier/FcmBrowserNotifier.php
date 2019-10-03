<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier;

use Gdbots\Pbj\Message;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\UriTemplate\UriTemplateService;

class FcmBrowserNotifier extends AbstractFcmNotifier
{
    const DISABLED_FLAG_NAME = 'fcm_browser_notifier_disabled';

    /**
     * {@inheritdoc}
     */
    protected function buildPayload(Message $notification, Message $app, ?Message $content = null): array
    {
        $payload = parent::buildPayload($notification, $app, $content);
        $payload['data'] = [
            'notification_ref' => NodeRef::fromNode($notification)->toString(),
        ];

        if (null !== $content) {
            $contentRef = NodeRef::fromNode($content);
            // fixme: we can only use one payload type, either data or webPush
            $payload['data']['node_ref'] = $contentRef->toString();
            $url = UriTemplateService::expand(
                "{$content::schema()->getQName()}.canonical",
                $content->getUriTemplateVars()
            );

            if (!empty($url)) {
                $payload['webpush'] = [
                    'fcm_options' => [
                        'link' => $url,
                    ],
                ];
            }
        }

        return $payload;
    }
}
