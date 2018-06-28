<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractDeleteNodeHandler;
use Gdbots\Pbj\Schema;
use Gdbots\Pbj\SchemaCurie;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;

class DeleteNotificationHandler extends AbstractDeleteNodeHandler
{
    use NotificationPbjxHelperTrait;

    /**
     * {@inheritdoc}
     */
    public static function handlesCuries(): array
    {
        /** @var Schema $schema */
        $schema = NotificationV1Mixin::findAll()[0];
        $curie = $schema->getCurie();
        return [
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:command:delete-notification"),
        ];
    }
}
