<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Iam\Node\IosAppV1;
use Acme\Schemas\Notify\Command\DeleteNotificationV1;
use Acme\Schemas\Notify\Event\NotificationDeleted;
use Acme\Schemas\Notify\Node\IosNotificationV1;
use Acme\Schemas\Ovp\Node\VideoV1;
use Gdbots\Schemas\Ncr\NodeRef;
use Gdbots\Schemas\Pbjx\Mixin\Event\Event;
use Gdbots\Schemas\Pbjx\StreamId;
use Triniti\Notify\DeleteNotificationHandler;

final class DeleteNotificationHandlerTest extends AbstractPbjxTest
{
    public function testHandleCommand(): void
    {
        $node = IosNotificationV1::create()
            ->set('app_ref', NodeRef::fromNode(IosAppV1::create()))
            ->set('content_ref', NodeRef::fromNode(VideoV1::create()));

        $nodeRef = NodeRef::fromNode($node);
        $this->ncr->putNode($node);

        $expectedId = $nodeRef->getId();
        $command = DeleteNotificationV1::create();
        $command->set('node_ref', $nodeRef);

        $handler = new DeleteNotificationHandler($this->ncr);
        $handler->handleCommand($command, $this->pbjx);

        $this->eventStore->pipeAllEvents(function (Event $event, StreamId $streamId) use ($expectedId) {
            $this->assertInstanceOf(NotificationDeleted::class, $event);
            $this->assertSame(StreamId::fromString("ios-notification.history:{$expectedId}")->toString(), $streamId->toString());
        });
    }
}
