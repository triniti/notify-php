<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractCreateNodeHandler;
use Gdbots\Ncr\Ncr;
use Gdbots\Pbj\Schema;
use Gdbots\Pbj\SchemaCurie;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\Mixin\CreateNode\CreateNode;
use Gdbots\Schemas\Ncr\Mixin\NodeCreated\NodeCreated;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;

class CreateNotificationHandler extends AbstractCreateNodeHandler
{
    use NotificationPbjxHelperTrait;

    /**
     * @param Ncr $ncr
     */
    public function __construct(Ncr $ncr)
    {
        $this->ncr = $ncr;
    }

    /**
     * {@inheritdoc}
     */
    protected function beforePutEvents(NodeCreated $event, CreateNode $command, Pbjx $pbjx): void
    {
        parent::beforePutEvents($event, $command, $pbjx);

        /** @var Notification $node */
        $node = $event->get('node');
        $node
            ->set('status', NodeStatus::PUBLISHED())
            ->clear('sent_at');

        $this->applySchedule($node);
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
            SchemaCurie::fromString("{$curie->getVendor()}:{$curie->getPackage()}:command:create-notification"),
        ];
    }
}
