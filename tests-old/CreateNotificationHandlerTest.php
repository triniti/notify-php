<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\AndroidAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Command\CreateNotificationV1;
use Acme\Schemas\Notify\Event\NotificationCreatedV1;
use Acme\Schemas\Notify\Node\AndroidNotificationV1;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Gdbots\Schemas\Pbjx\StreamId;
use Triniti\Notify\CreateNotificationHandler;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

final class CreateNotificationHandlerTestOld extends AbstractPbjxTest
{
    public function testHandleCommand(): void
    {
        $app = AndroidAppV1::create();
        $appRef = NodeRef::fromNode($app);
        $content = ArticleV1::create()->set('published_at', new \DateTime('2018-12-25'));
        $contentRef = NodeRef::fromNode($content);
        $this->ncr->putNode($content);

        $node = AndroidNotificationV1::create()
            ->set('app_ref', $appRef)
            ->set('content_ref', $contentRef)
            ->set('send_on_publish', true)
            ->set('body', 'new article here');

        $command = CreateNotificationV1::create()->set('node', $node);

        $expectedEvent = NotificationCreatedV1::create();
        $expectedId = $node->get('_id');

        $handler = new CreateNotificationHandler($this->ncr);
        $handler->handleCommand($command, $this->pbjx);

        $this->eventStore->pipeAllEvents(function (Event $event, StreamId $streamId)
        use ($expectedEvent, $expectedId, $appRef, $content, $contentRef) {
            $actualNode = $event->get('node');
            $this->assertSame($event::schema(), $expectedEvent::schema());
            $this->assertSame(NodeStatus::PUBLISHED(), $actualNode->get('status'));
            $this->assertSame(NotificationSendStatus::SCHEDULED(), $actualNode->get('send_status'));
            $this->assertTrue($appRef->equals($actualNode->get('app_ref')));
            $this->assertTrue($contentRef->equals($actualNode->get('content_ref')));
            $this->assertTrue($actualNode->get('send_on_publish'));
            $this->assertEquals($content->get('published_at')->modify('+10 seconds'), $actualNode->get('send_at'));
            $this->assertSame('new article here', $actualNode->get('body'));
            $this->assertSame(StreamId::fromString("android-notification.history:{$expectedId}")->toString(), $streamId->toString());
            $this->assertSame($event->generateMessageRef()->toString(), (string)$actualNode->get('last_event_ref'));
        });
    }
}
