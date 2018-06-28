<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractNodeCommandHandler;
use Gdbots\Ncr\Exception\NodeNotFound;
use Gdbots\Ncr\Ncr;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Schemas\Iam\Mixin\App\App;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Enum\Code;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\Mixin\NotificationFailed\NotificationFailed;
use Triniti\Schemas\Notify\Mixin\NotificationFailed\NotificationFailedV1Mixin;
use Triniti\Schemas\Notify\Mixin\NotificationSent\NotificationSent;
use Triniti\Schemas\Notify\Mixin\NotificationSent\NotificationSentV1Mixin;
use Triniti\Schemas\Notify\Mixin\SendNotification\SendNotification;
use Triniti\Schemas\Notify\Mixin\SendNotification\SendNotificationV1Mixin;
use Triniti\Schemas\Notify\NotifierResult;
use Triniti\Schemas\Notify\NotifierResultV1;

class SendNotificationHandler extends AbstractNodeCommandHandler
{
    use NotificationPbjxHelperTrait;

    /** @var Ncr */
    protected $ncr;

    /** @var NotifierLocator */
    protected $locator;

    /**
     * @param Ncr             $ncr
     * @param NotifierLocator $locator
     */
    public function __construct(Ncr $ncr, NotifierLocator $locator)
    {
        $this->ncr = $ncr;
        $this->locator = $locator;
    }

    /**
     * @param SendNotification $command
     * @param Pbjx             $pbjx
     */
    protected function handle(SendNotification $command, Pbjx $pbjx): void
    {
        /** @var NodeRef $nodeRef */
        $nodeRef = $command->get('node_ref');
        $context = $this->createNcrContext($command);

        try {
            /** @var Notification $notification */
            $notification = $this->ncr->getNode($nodeRef, true, $context);
        } catch (NodeNotFound $nf) {
            // doesn't exist, ignore
            return;
        } catch (\Throwable $e) {
            throw $e;
        }

        $this->assertIsNodeSupported($notification);

        /** @var NodeRef $appRef */
        $appRef = $notification->get('app_ref');

        /** @var NodeRef $contentRef */
        $contentRef = $notification->get('content_ref');

        $refs = [$appRef];
        if (null !== $contentRef) {
            $refs[] = $contentRef;
        }

        $nodes = $this->ncr->getNodes($refs, true, $context);
        $app = $nodes[$appRef->toString()] ?? null;
        $content = null !== $contentRef ? ($nodes[$contentRef->toString()] ?? null) : null;
        $result = null;

        if (!$app instanceof App || !$this->isSupportedByApp($nodeRef->getQName(), $appRef)) {
            $result = NotifierResultV1::create()
                ->set('ok', false)
                ->set('code', Code::NOT_FOUND)
                ->set('error_name', 'NodeNotFound')
                ->set('error_message', "App [{$appRef}] was not found.");
        }

        if (null !== $contentRef) {
            if (!$content instanceof HasNotifications) {
                $result = NotifierResultV1::create()
                    ->set('ok', false)
                    ->set('code', Code::INVALID_ARGUMENT)
                    ->set('error_name', 'InvalidNotificationContent')
                    ->set('error_message', "Selected content [{$contentRef}] does not support notifications.");
            } elseif (!NodeStatus::PUBLISHED()->equals($content->get('status'))) {
                $result = NotifierResultV1::create()
                    ->set('ok', false)
                    ->set('code', Code::ABORTED)
                    ->set('error_name', 'UnpublishedNotificationContent')
                    ->set('error_message', "Selected content [{$contentRef}] is [{$content->get('status')}].");
            }
        }

        if (null === $result) {
            $notifer = $this->locator->getNotifier($notification::schema()->getCurie());
            $result = $notifer->send($notification, $app, $content);
        }

        if ($result->get('ok')) {
            $event = $this->createNotificationSent($command, $pbjx, $notification, $result, $app, $content);
        } else {
            $event = $this->createNotificationFailed($command, $pbjx, $notification, $result, $app, $content);
        }

        $this->putEvents($command, $pbjx, $this->createStreamId($nodeRef, $command, $event), [$event]);
    }

    /**
     * @param SendNotification $command
     * @param Pbjx             $pbjx
     * @param Notification     $notification
     * @param NotifierResult   $result
     * @param App              $app
     * @param HasNotifications $content
     *
     * @return NotificationSent
     */
    protected function createNotificationSent(
        SendNotification $command,
        Pbjx $pbjx,
        Notification $notification,
        NotifierResult $result,
        App $app,
        ?HasNotifications $content = null
    ): NotificationSent {
        /** @var NotificationSent $event */
        $event = NotificationSentV1Mixin::findOne()->createMessage();
        $pbjx->copyContext($command, $event);
        return $event
            ->set('node_ref', $command->get('node_ref'))
            ->set('notifier_result', $result);
    }

    /**
     * @param SendNotification $command
     * @param Pbjx             $pbjx
     * @param Notification     $notification
     * @param NotifierResult   $result
     * @param App              $app
     * @param HasNotifications $content
     *
     * @return NotificationFailed
     */
    protected function createNotificationFailed(
        SendNotification $command,
        Pbjx $pbjx,
        Notification $notification,
        NotifierResult $result,
        App $app,
        ?HasNotifications $content = null
    ): NotificationFailed {
        /** @var NotificationFailed $event */
        $event = NotificationFailedV1Mixin::findOne()->createMessage();
        $pbjx->copyContext($command, $event);
        return $event
            ->set('node_ref', $command->get('node_ref'))
            ->set('notifier_result', $result);
    }

    /**
     * {@inheritdoc}
     */
    public static function handlesCuries(): array
    {
        return [
            SendNotificationV1Mixin::findOne()->getCurie(),
        ];
    }
}
