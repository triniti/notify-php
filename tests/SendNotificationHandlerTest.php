<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\IosAppV1;
use Acme\Schemas\News\Command\PublishArticleV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Command\CreateNotificationV1;
use Acme\Schemas\Notify\Command\SendNotificationV1;
use Acme\Schemas\Notify\Node\IosNotificationV1;
use Triniti\News\ArticleAggregate;
use Triniti\Notify\NotificationAggregate;
use Triniti\Notify\SendNotificationHandler;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

final class SendNotificationHandlerTest extends AbstractPbjxTest
{
    public function testSendNotification(): void
    {
        $content = ArticleV1::create();
        $contentRef = $content->generateNodeRef();
        $contentAggregate = ArticleAggregate::fromNodeRef($contentRef, $this->pbjx);
        $contentAggregate->publishNode(PublishArticleV1::create()->set('node_ref', $contentRef));
        $contentAggregate->commit();

        $app = IosAppV1::create();
        $notification = IosNotificationV1::create()
            ->set('content_ref', $content->generateNodeRef())
            ->set('app_ref', $app->generateNodeRef())
            ->set('send_at', new \DateTime('NOW'));
        $notificationRef = $notification->generateNodeRef();
        $notificationAggregate = NotificationAggregate::fromNodeRef($notificationRef, $this->pbjx);
        $notificationAggregate->createNode(CreateNotificationV1::create()->set('node', $notification));
        $notificationAggregate->commit();

        // todo: this may not need ncr as a constructor param
        $handler = new SendNotificationHandler($this->ncr, new MockNotifierLocator());
        $command = SendNotificationV1::create()->set('node_ref', $notificationRef);
        $handler->handleCommand($command, $this->pbjx);

    }
}
