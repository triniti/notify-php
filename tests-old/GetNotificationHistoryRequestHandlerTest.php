<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\SlackAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Event\NotificationCreatedV1;
use Acme\Schemas\Notify\Event\NotificationUpdatedV1;
use Acme\Schemas\Notify\Node\SlackNotificationV1;
use Acme\Schemas\Notify\Request\GetNotificationHistoryRequestV1;
use Acme\Schemas\Notify\Request\GetNotificationHistoryResponse;
use Gdbots\Pbj\Message;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\StreamId;
use Triniti\Notify\GetNotificationHistoryRequestHandler;

final class GetNotificationHistoryRequestHandlerTest extends AbstractPbjxTest
{
    public function testHandleRequest(): void
    {
        $node = SlackNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(SlackAppV1::create()))
            ->set('content_ref', NodeRef::fromNode(ArticleV1::create()));
        $streamId = StreamId::fromString("slack-notification.history:{$node->get('_id')}");
        $newNode = $node->set('body', 'update costom message');

        $expectedEvents = [
            NotificationCreatedV1::create()->set('node', $node),
            NotificationUpdatedV1::create()->set('node_ref', NodeRef::fromNode($node))->set('new_node', $newNode),
        ];
        $this->pbjx->getEventStore()->putEvents($streamId, $expectedEvents);
        $request = GetNotificationHistoryRequestV1::create()->set('stream_id', $streamId);

        /** @var GetNotificationHistoryResponse $response */
        $response = (new GetNotificationHistoryRequestHandler())->handleRequest($request, $this->pbjx);

        $actualEvents = $response->get('events');
        $this->assertFalse($response->get('has_more'));
        $this->assertEquals(2, count($actualEvents));

        /** @var Message $actualEvent */
        foreach (array_reverse($actualEvents) as $key => $actualEvent) {
            $this->assertTrue($actualEvent->equals($expectedEvents[$key]));
        }
    }

    public function testHandleRequestWithCount(): void
    {
        $node = SlackNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(SlackAppV1::create()))
            ->set('content_ref', NodeRef::fromNode(ArticleV1::create()));
        $streamId = StreamId::fromString("slack-notification.history:{$node->get('_id')}");
        $newNode = $node->set('body', 'update costom message');

        $expectedEvents = [
            NotificationCreatedV1::create()->set('node', $node),
            NotificationUpdatedV1::create()->set('node_ref', NodeRef::fromNode($node))->set('new_node', $newNode),
        ];

        $this->pbjx->getEventStore()->putEvents($streamId, $expectedEvents);
        $request = GetNotificationHistoryRequestV1::create()
            ->set('stream_id', $streamId)
            ->set('count', 1)
            ->set('forward', true);

        /** @var GetNotificationHistoryResponse $response */
        $response = (new GetNotificationHistoryRequestHandler())->handleRequest($request, $this->pbjx);

        $actualEvents = $response->get('events');
        $this->assertTrue($response->get('has_more'));
        $this->assertEquals(1, count($actualEvents));

        /** @var Message $actualEvent */
        foreach ($actualEvents as $key => $actualEvent) {
            $this->assertTrue($actualEvent->equals($expectedEvents[$key]));
        }
    }

    public function testHandleRequestWithForward(): void
    {
        $node = SlackNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(SlackAppV1::create()))
            ->set('content_ref', NodeRef::fromNode(ArticleV1::create()));
        $streamId = StreamId::fromString("slack-notification.history:{$node->get('_id')}");
        $newNode = $node->set('body', 'update costom message');

        $expectedEvents = [
            NotificationCreatedV1::create()->set('node', $node),
            NotificationUpdatedV1::create()->set('node_ref', NodeRef::fromNode($node))->set('new_node', $newNode),
        ];

        $this->pbjx->getEventStore()->putEvents($streamId, $expectedEvents);
        $request = GetNotificationHistoryRequestV1::create()
            ->set('stream_id', $streamId)
            ->set('forward', true);

        /** @var GetNotificationHistoryResponse $response */
        $response = (new GetNotificationHistoryRequestHandler())->handleRequest($request, $this->pbjx);

        $actualEvents = $response->get('events');
        $this->assertFalse($response->get('has_more'));
        $this->assertEquals(2, count($actualEvents));

        /** @var Message $actualEvent */
        foreach ($actualEvents as $key => $actualEvent) {
            $this->assertTrue($actualEvent->equals($expectedEvents[$key]));
        }
    }
}
