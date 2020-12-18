<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AggregateResolver;
use Gdbots\Ncr\Ncr;
use Gdbots\Pbj\Message;
use Gdbots\Pbj\MessageResolver;
use Gdbots\Pbj\WellKnown\NodeRef;
use Gdbots\Pbjx\CommandHandler;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Pbjx\Enum\Code;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Event\NotificationFailedV1;
use Triniti\Schemas\Notify\Event\NotificationSentV1;
use Triniti\Schemas\Notify\NotifierResultV1;

class SendNotificationHandler implements CommandHandler
{
    use NotificationPbjxHelperTrait;

    protected Ncr $ncr;
    protected NotifierLocator $locator;

    public function __construct(Ncr $ncr, NotifierLocator $locator)
    {
        $this->ncr = $ncr;
        $this->locator = $locator;
    }

    public function handleCommand(Message $command, Pbjx $pbjx): void
    {
        /** @var NodeRef $nodeRef */
        $nodeRef = $command->get('node_ref');
//        $context = $this->createNcrContext($command);

        $notificationAggregate = NotificationAggregate::fromNodeRef($nodeRef, $pbjx);
        $notificationAggregate->sync();
        $notification = $notificationAggregate->getNode();

        if (!$notification->get('send_status')->equals(NotificationSendStatus::SCHEDULED())) {
            return;
        }

        if (!$this->isNodeSupported($notification)) {
            return;
        }

        /** @var NodeRef $appRef */
        $appRef = $notification->get('app_ref');
        $appAggregate = AggregateResolver::resolve($appRef->getQName())::fromNodeRef($appRef, $pbjx);
        $appAggregate->sync();
        $app = $appAggregate->getNode();

        /** @var NodeRef $contentRef */
        $contentRef = $notification->get('content_ref');
        $contentAggregate = AggregateResolver::resolve($contentRef->getQName())::fromNodeRef($contentRef, $pbjx);
        $contentAggregate->sync();
        $content = $contentAggregate->getNode();

//        $refs = [$appRef];
//        if (null !== $contentRef) {
//            $refs[] = $contentRef;
//        }

//        $nodes = $this->ncr->getNodes($refs, true, $context);
//        $app = $nodes[$appRef->toString()] ?? null;
//        $content = null !== $contentRef ? ($nodes[$contentRef->toString()] ?? null) : null;
        $result = null;

        if (
            !$app::schema()->hasMixin('gdbots:iam:mixin:app')
            || !$this->isSupportedByApp($nodeRef->getQName(), $appRef)
        ) {
            $result = NotifierResultV1::create()
                ->set('ok', false)
                ->set('code', Code::NOT_FOUND)
                ->set('error_name', 'NodeNotFound')
                ->set('error_message', "App [{$appRef}] was not found.");
        }

        if (null !== $contentRef) {
            if (!$content::schema()->hasMixin('triniti:notify:mixin:has-notifications')) {
                $result = NotifierResultV1::create()
                    ->set('ok', false)
                    ->set('code', Code::INVALID_ARGUMENT)
                    ->set('error_name', 'InvalidNotificationContent')
                    ->set('error_message', "Selected content [{$contentRef}] does not support notifications.");
            } elseif (
                !NodeStatus::PUBLISHED()->equals($content->get('status'))
                && 'delete' !== $notification->get('apple_news_operation')
            ) {
                // If notification is an apple news delete operation then it's ok the send notification against and unpublished article
                $result = NotifierResultV1::create()
                    ->set('ok', false)
                    ->set('code', Code::ABORTED)
                    ->set('error_name', 'UnpublishedNotificationContent')
                    ->set('error_message', "Selected content [{$contentRef}] is [{$content->get('status')}].");
            }
        }

        if (null === $result) {
            $notifier = $this->locator->getNotifier($notification::schema()->getCurie());
            $result = $notifier->send($notification, $app, $content);
        }

        $retryCodes = [ // todo: this doesnt need to be local eh?
            Code::ABORTED            => true,
            Code::DEADLINE_EXCEEDED  => true,
            Code::INTERNAL           => true,
            Code::RESOURCE_EXHAUSTED => true,
            Code::UNAVAILABLE        => true,
            Code::UNKNOWN            => true,
        ];

        if (
            !$result->get('ok')
            && $command->get('ctx_retries') < 3
            && isset($retryCodes[$result->get('code')])
        ) {
            // reschedule notification if service is down.
            $newCommand = clone $command;
            $retries = $newCommand->get('ctx_retries') + 1;
            $timestamp = strtotime('+' . (120 * $retries) . ' seconds');
            $newCommand->set('ctx_retries', $retries);
            $pbjx->copyContext($command, $newCommand);
            $pbjx->sendAt($newCommand, $timestamp, "{$nodeRef}.send");
            return;
        }

        if ($result->get('ok')) {
            $notificationAggregate->sendNotification($command, $result);
        } else {
            $notificationAggregate->failNotification($command, $result);
        }

//        if ($result->get('ok')) {
//            $event = $this->createNotificationSent($command, $pbjx, $notification, $result, $app, $content);
//        } elseif ($command->get('ctx_retries') < 3 && isset($retryCodes[$result->get('code')])) {
//            // reschedule notification if service is down.
//            $newCommand = clone $command;
//            $retries = $newCommand->get('ctx_retries') + 1;
//            $timestamp = strtotime('+' . (120 * $retries) . ' seconds');
//            $newCommand->set('ctx_retries', $retries);
//            $pbjx->copyContext($command, $newCommand);
//            $pbjx->sendAt($newCommand, $timestamp, "{$nodeRef}.send");
//            return;
//        } else {
//            $event = $this->createNotificationFailed($command, $pbjx, $notification, $result, $app, $content);
//        }
//
//        $this->putEvents($command, $pbjx, $this->createStreamId($nodeRef, $command, $event), [$event]);
    }

//    /**
//     * @param SendNotification $command
//     * @param Pbjx             $pbjx
//     */
//    protected function handle(SendNotification $command, Pbjx $pbjx): void
//    {
//        /** @var NodeRef $nodeRef */
//        $nodeRef = $command->get('node_ref');
//        $context = $this->createNcrContext($command);
//
//        try {
//            /** @var Notification $notification */
//            $notification = $this->ncr->getNode($nodeRef, true, $context);
//        } catch (NodeNotFound $nf) {
//            // doesn't exist, ignore
//            return;
//        } catch (\Throwable $e) {
//            throw $e;
//        }
//
//        if (!$notification->get('send_status')->equals(NotificationSendStatus::SCHEDULED())) {
//            return;
//        }
//
//        if (!$this->isNodeSupported($notification)) {
//            return;
//        }
//
//        /** @var NodeRef $appRef */
//        $appRef = $notification->get('app_ref');
//
//        /** @var NodeRef $contentRef */
//        $contentRef = $notification->get('content_ref');
//
//        $refs = [$appRef];
//        if (null !== $contentRef) {
//            $refs[] = $contentRef;
//        }
//
//        $nodes = $this->ncr->getNodes($refs, true, $context);
//        $app = $nodes[$appRef->toString()] ?? null;
//        $content = null !== $contentRef ? ($nodes[$contentRef->toString()] ?? null) : null;
//        $result = null;
//
//        if (!$app instanceof App || !$this->isSupportedByApp($nodeRef->getQName(), $appRef)) {
//            $result = NotifierResultV1::create()
//                ->set('ok', false)
//                ->set('code', Code::NOT_FOUND)
//                ->set('error_name', 'NodeNotFound')
//                ->set('error_message', "App [{$appRef}] was not found.");
//        }
//
//        if (null !== $contentRef) {
//            if (!$content instanceof HasNotifications) {
//                $result = NotifierResultV1::create()
//                    ->set('ok', false)
//                    ->set('code', Code::INVALID_ARGUMENT)
//                    ->set('error_name', 'InvalidNotificationContent')
//                    ->set('error_message', "Selected content [{$contentRef}] does not support notifications.");
//                // If notification is a apple news delete operation then it ok the send notification against and unpublished article
//            } elseif (!NodeStatus::PUBLISHED()->equals($content->get('status')) && 'delete' !== $notification->get('apple_news_operation')) {
//                $result = NotifierResultV1::create()
//                    ->set('ok', false)
//                    ->set('code', Code::ABORTED)
//                    ->set('error_name', 'UnpublishedNotificationContent')
//                    ->set('error_message', "Selected content [{$contentRef}] is [{$content->get('status')}].");
//            }
//        }
//
//        if (null === $result) {
//            $notifer = $this->locator->getNotifier($notification::schema()->getCurie());
//            $result = $notifer->send($notification, $app, $content);
//        }
//
//        $retryCodes = [
//            Code::ABORTED            => true,
//            Code::DEADLINE_EXCEEDED  => true,
//            Code::INTERNAL           => true,
//            Code::RESOURCE_EXHAUSTED => true,
//            Code::UNAVAILABLE        => true,
//            Code::UNKNOWN            => true,
//        ];
//
//        if ($result->get('ok')) {
//            $event = $this->createNotificationSent($command, $pbjx, $notification, $result, $app, $content);
//
//        } elseif ($command->get('ctx_retries') < 3 && isset($retryCodes[$result->get('code')])) {
//            // reschedule notification if service is down.
//            $newCommand = clone $command;
//            $retries = $newCommand->get('ctx_retries') + 1;
//            $timestamp = strtotime('+' . (120 * $retries) . ' seconds');
//            $newCommand->set('ctx_retries', $retries);
//            $pbjx->copyContext($command, $newCommand);
//            $pbjx->sendAt($newCommand, $timestamp, "{$nodeRef}.send");
//            return;
//
//        } else {
//            $event = $this->createNotificationFailed($command, $pbjx, $notification, $result, $app, $content);
//        }
//
//        $this->putEvents($command, $pbjx, $this->createStreamId($nodeRef, $command, $event), [$event]);
//    }

    /**
     * @param Message $command
     * @param Pbjx    $pbjx
     * @param Message $notification
     * @param Message $result
     * @param Message $app
     * @param Message $content
     *
     * @return Message
     */
    protected function createNotificationSent(
        Message $command,
        Pbjx $pbjx,
        Message $notification,
        Message $result,
        Message $app,
        ?Message $content = null
    ): Message {
        $event = NotificationSentV1::create();
        $pbjx->copyContext($command, $event);
        return $event
            ->set('node_ref', $command->get('node_ref'))
            ->set('notifier_result', $result);
    }

    /**
     * @param Message $command
     * @param Pbjx    $pbjx
     * @param Message $notification
     * @param Message $result
     * @param Message $app
     * @param Message $content
     *
     * @return Message
     */
    protected function createNotificationFailed(
        Message $command,
        Pbjx $pbjx,
        Message $notification,
        Message $result,
        Message $app,
        ?Message $content = null
    ): Message {
        $event = NotificationFailedV1::create();
        $pbjx->copyContext($command, $event);
        return $event
            ->set('node_ref', $command->get('node_ref'))
            ->set('notifier_result', $result);
    }

    public static function handlesCuries(): array
    {
        // deprecated mixins, will be removed in 3.x
        $curies = MessageResolver::findAllUsingMixin('triniti:notify:mixin:send-notification:v1', false);
        $curies[] = 'triniti:notify:command:send-notification';
        return $curies;
    }
}
