<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\SmsAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Node\SmsNotificationV1;
use Acme\Schemas\Notify\Request\GetNotificationRequestV1;
use Gdbots\Schemas\Ncr\NodeRef;
use Triniti\Notify\GetNotificationRequestHandler;

final class GetNotificationRequestHandlerTest extends AbstractPbjxTest
{
    public function testGetByNodeRefThatExists(): void
    {
        $node = SmsNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(SmsAppV1::create()))
            ->set('content_ref', NodeRef::fromNode(ArticleV1::create()));
        $nodeRef = NodeRef::fromNode($node);

        $this->ncr->putNode($node);

        $request = GetNotificationRequestV1::create()->set('node_ref', $nodeRef);
        $handler = new GetNotificationRequestHandler($this->ncr);

        /** @var GetNotificationRequestV1 $response */
        $response = $handler->handleRequest($request, $this->pbjx);

        /** @var SmsNotificationV1 $actualNode */
        $actualNode = $response->get('node');
        $this->assertTrue($actualNode->equals($node));
    }

    /**
     * @expectedException \Gdbots\Ncr\Exception\NodeNotFound
     */
    public function testGetByNodeRefThatDoesNotExist(): void
    {
        $nodeRef = NodeRef::fromString('triniti:notify:ios-notification:1234');
        $request = GetNotificationRequestV1::create()->set('node_ref', $nodeRef);
        $handler = new GetNotificationRequestHandler($this->ncr);
        $handler->handleRequest($request, $this->pbjx);
    }

    /**
     * @expectedException \Gdbots\Ncr\Exception\NodeNotFound
     */
    public function testGetByNothing(): void
    {
        $request = GetNotificationRequestV1::create();
        $handler = new GetNotificationRequestHandler($this->ncr);
        $handler->handleRequest($request, $this->pbjx);
    }
}
