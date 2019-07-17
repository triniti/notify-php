<?php
declare(strict_types=1);

namespace Triniti\Notify\Validator;

use Gdbots\Pbj\Assertion;
use Gdbots\Pbj\Message;
use Gdbots\Pbj\Schema;
use Gdbots\Pbjx\DependencyInjection\PbjxValidator;
use Gdbots\Pbjx\Event\PbjxEvent;
use Gdbots\Pbjx\EventSubscriber;
use Gdbots\Schemas\Ncr\NodeRef;
use Triniti\Notify\Exception\NotificationAlreadyScheduled;
use Triniti\Notify\Exception\NotificationAlreadySent;
use Triniti\Notify\NotificationPbjxHelperTrait;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Mixin\AppleNewsNotification\AppleNewsNotification;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;
use Triniti\Schemas\Notify\Mixin\SearchNotificationsRequest\SearchNotificationsRequestV1Mixin;

class NotificationValidator implements EventSubscriber, PbjxValidator
{
    use NotificationPbjxHelperTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        /** @var Schema $schema */
        $schema = NotificationV1Mixin::findAll()[0];
        $curie = $schema->getCurie();
        $prefix = "{$curie->getVendor()}:{$curie->getPackage()}:command:";

        return [
            "{$prefix}create-notification.validate" => 'validateCreateNotification',
            "{$prefix}update-notification.validate" => 'validateUpdateNotification',
        ];
    }

    /**
     * @param PbjxEvent $pbjxEvent
     */
    public function validateCreateNotification(PbjxEvent $pbjxEvent): void
    {
        $command = $pbjxEvent->getMessage();
        Assertion::true($command->has('node'), 'Field "node" is required.', 'node');

        /** @var Notification $node */
        $node = $command->get('node');
        Assertion::true($node->has('app_ref'), 'Field "node.app_ref" is required.', 'node.app_ref');

        /** @var NodeRef $appRef */
        $appRef = $node->get('app_ref');
        $nodeRef = NodeRef::fromNode($node);

        Assertion::true(
            $this->isSupportedByApp($nodeRef->getQName(), $appRef),
            sprintf(
                'The app [%s] does not support the [%s].',
                $appRef->toString(),
                $nodeRef->getQName()->toString()
            ),
            'node.app_ref'
        );

        if ($node->has('content_ref')) {
            $this->ensureNotAlreadyScheduled($pbjxEvent, $node);
        }
    }

    /**
     * @param PbjxEvent $pbjxEvent
     */
    public function validateUpdateNotification(PbjxEvent $pbjxEvent): void
    {
        $command = $pbjxEvent->getMessage();
        Assertion::true($command->has('new_node'), 'Field "new_node" is required.', 'new_node');

        /** @var Notification $oldNode */
        $oldNode = $command->get('old_node');

        /** @var Notification $newNode */
        $newNode = $command->get('new_node');
        Assertion::true($newNode->has('app_ref'), 'Field "new_node.app_ref" is required.', 'new_node.app_ref');

        /** @var NodeRef $appRef */
        $appRef = $newNode->get('app_ref');
        $nodeRef = NodeRef::fromNode($newNode);

        Assertion::true(
            $this->isSupportedByApp($nodeRef->getQName(), $newNode->get('app_ref')),
            sprintf(
                'The app [%s] does not support the [%s].',
                $appRef->toString(),
                $nodeRef->getQName()->toString()
            ),
            'new_node.app_ref'
        );

        // we trust the old node here because the server binds it
        // at the start of the request
        if ($this->alreadySent($oldNode)) {
            throw new NotificationAlreadySent();
        }
    }

    /**
     * @param PbjxEvent $event
     * @param Message   $notification
     *
     * @throws NotificationAlreadyScheduled
     */
    protected function ensureNotAlreadyScheduled(PbjxEvent $event, Message $notification): void
    {
        /** @var NodeRef $appRef */
        $appRef = $notification->get('app_ref');

        /** @var NodeRef $contentRef */
        $contentRef = $notification->get('content_ref');

        $request = SearchNotificationsRequestV1Mixin::findOne()->createMessage()
            ->set('app_ref', $appRef)
            ->set('content_ref', $contentRef)
            ->set('send_status', NotificationSendStatus::SCHEDULED())
            ->set('count', 1);

        /** @var Message $response */
        $response = $event::getPbjx()->copyContext($event->getMessage(), $request)->request($request);

        if ($response->has('nodes')) {
            throw new NotificationAlreadyScheduled();
        }
    }
}
