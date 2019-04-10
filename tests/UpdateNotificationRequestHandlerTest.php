<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\BrowserAppV1;
use Acme\Schemas\Notify\Command\UpdateNotificationV1;
use Acme\Schemas\Notify\Event\NotificationUpdatedV1;
use Acme\Schemas\Notify\Node\BrowserNotificationV1;
use Acme\Schemas\Ovp\Node\VideoV1;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Gdbots\Schemas\Pbjx\StreamId;
use Triniti\Notify\UpdateNotificationHandler;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

final class UpdateNotificationRequestHandlerTest extends AbstractPbjxTest
{
    public function testHandle(): void
    {
        $videoNode = VideoV1::create();
        $this->ncr->putNode($videoNode);

        $oldNode = BrowserNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(BrowserAppV1::create()))
            ->set('content_ref', NodeRef::fromNode($videoNode))
            ->set('send_status', NotificationSendStatus::SCHEDULED())
            ->set('send_at', new \DateTime('2020-01-01'));

        $this->ncr->putNode($oldNode);

        $newNode = BrowserNotificationV1::create()
            ->set('_id', $oldNode->get('_id'))
            ->set('app_ref', NodeRef::fromNode(BrowserAppV1::create()))
            ->set('content_ref', NodeRef::fromNode($videoNode))
            ->set('send_at', new \DateTime('2020-01-02'))
            ->set('body', 'message updated');

        $command = UpdateNotificationV1::create()
            ->set('node_ref', NodeRef::fromNode($oldNode))
            ->set('old_node', $oldNode)
            ->set('new_node', $newNode);

        $handler = new UpdateNotificationHandler($this->ncr);
        $handler->handleCommand($command, $this->pbjx);

        $expectedEvent = NotificationUpdatedV1::create();
        $expectedId = $oldNode->get('_id');
        $this->eventStore->pipeAllEvents(function (Event $event, StreamId $streamId) use ($expectedEvent, $expectedId) {
            $this->assertSame($event::schema(), $expectedEvent::schema());
            $this->assertTrue($event->has('old_node'));
            $this->assertTrue($event->has('new_node'));

            $newNodeFromEvent = $event->get('new_node');

            $this->assertEquals(new \DateTime('2020-01-02'), $newNodeFromEvent->get('send_at'));
            $this->assertSame(NodeStatus::PUBLISHED, (string)$newNodeFromEvent->get('status'));
            $this->assertSame(NotificationSendStatus::SCHEDULED, (string)$newNodeFromEvent->get('send_status'));
            $this->assertSame(StreamId::fromString("browser-notification.history:{$expectedId}")->toString(), $streamId->toString());
            $this->assertSame($event->generateMessageRef()->toString(), (string)$newNodeFromEvent->get('last_event_ref'));
        });
    }
}
