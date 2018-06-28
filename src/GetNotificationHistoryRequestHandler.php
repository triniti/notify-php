<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractGetNodeHistoryRequestHandler;
use Gdbots\Pbj\Schema;
use Gdbots\Pbj\SchemaCurie;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Pbjx\Mixin\GetEventsRequest\GetEventsRequest;
use Gdbots\Schemas\Pbjx\StreamId;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;

class GetNotificationHistoryRequestHandler extends AbstractGetNodeHistoryRequestHandler
{
    /**
     * {@inheritdoc}
     */
    protected function canReadStream(GetEventsRequest $request, Pbjx $pbjx): bool
    {
        /** @var StreamId $streamId */
        $streamId = $request->get('stream_id');
        $validTopics = [];

        /** @var Schema $schema */
        foreach (NotificationV1Mixin::findAll() as $schema) {
            // e.g. "ios-notification.history", "android-notification.history"
            $validTopics[$schema->getQName()->getMessage() . '.history'] = true;
        }

        return isset($validTopics[$streamId->getTopic()]);
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
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:request:get-notification-history-request"),
        ];
    }
}
