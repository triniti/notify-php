<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Acme\Schemas\Notify\Event\NotificationCreatedV1;
use Acme\Schemas\Notify\Event\NotificationDeletedV1;
use Acme\Schemas\Notify\Event\NotificationUpdatedV1;
use Acme\Schemas\Notify\Node\AlexaNotificationV1;
use Gdbots\Ncr\NcrSearch;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Ncr\NodeRef;
use Triniti\Notify\NcrNotificationProjector;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

final class NcrNotificationProjectorTest extends AbstractPbjxTest
{
    /** @var NcrNotificationProjector */
    protected $projector;

    /** @var NcrSearch|\PHPUnit_Framework_MockObject_MockObject */
    protected $ncrSearch;

    public function setup()
    {
        parent::setup();
        $this->ncrSearch = $this->getMockBuilder(MockNcrSearch::class)->getMock();
        $this->projector = new NcrNotificationProjector($this->ncr, $this->ncrSearch);
    }

    public function testOnNotificationCreated(): void
    {
        $item = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $nodeRef = NodeRef::fromNode($item);
        $event = NotificationCreatedV1::create()->set('node', $item);

        $this->ncrSearch->expects($this->once())->method('indexNodes');
        $this->projector->onNotificationCreated($event, $this->pbjx);

        $actualArticle = $this->ncr->getNode($nodeRef);
        $this->assertTrue($item->equals($actualArticle));
    }

    public function testOnNotificationCreatedIsReplay(): void
    {
        $item = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $nodeRef = NodeRef::fromNode($item);
        $event = NotificationCreatedV1::create()->set('node', $item);
        $event->isReplay(true);

        $this->ncrSearch->expects($this->never())->method('indexNodes');
        $this->projector->onNotificationCreated($event, $this->pbjx);

        $actualArticle = $this->ncr->getNode($nodeRef);
        $this->assertTrue($item->equals($actualArticle));
    }

    public function testOnNotificationUpdated(): void
    {
        $oldItem = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $nodeRef = NodeRef::fromNode($oldItem);
        $this->ncr->putNode($oldItem);

        $newItem = AlexaNotificationV1::create()
            ->set('_id', $oldItem->get('_id'))
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $newItem->set('body', 'the content message');

        $event = NotificationUpdatedV1::create()
            ->set('old_node', $oldItem)
            ->set('new_node', $newItem)
            ->set('old_etag', $oldItem->get('etag'))
            ->set('new_etag', $newItem->get('etag'))
            ->set('node_ref', $nodeRef);

        $this->ncrSearch->expects($this->once())->method('indexNodes');
        $this->projector->onNotificationUpdated($event, $this->pbjx);

        $actualArticle = $this->ncr->getNode($nodeRef);
        $this->assertTrue($newItem->equals($actualArticle));
    }

    public function testOnNotificationUpdatedIsReplay(): void
    {
        $oldItem = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));
        $oldItem->set('body', 'Old body');

        $nodeRef = NodeRef::fromNode($oldItem);
        $this->ncr->putNode($oldItem);

        $newItem = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $newItem->set('body', 'New body')
            ->set('etag', $newItem->generateEtag(['etag', 'updated_at']));

        $event = NotificationUpdatedV1::create()
            ->set('old_node', $oldItem)
            ->set('new_node', $newItem)
            ->set('old_etag', $oldItem->get('etag'))
            ->set('new_etag', $newItem->get('etag'))
            ->set('node_ref', $nodeRef);

        $event->isReplay(true);
        $this->ncrSearch->expects($this->never())->method('indexNodes');
        $this->projector->onNotificationUpdated($event, $this->pbjx);

        $actualItem = $this->ncr->getNode($nodeRef);
        $this->assertTrue($actualItem->equals($oldItem));
    }

    public function testOnNotificationDeleted(): void
    {
        $item = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $nodeRef = NodeRef::fromNode($item);
        $this->ncr->putNode($item);

        $event = NotificationDeletedV1::create()->set('node_ref', $nodeRef);

        $this->projector->onNotificationDeleted($event, $this->pbjx);

        $deletedItem = $this->ncr->getNode($nodeRef);
        $this->assertEquals(NodeStatus::DELETED(), $deletedItem->get('status'));
        $this->assertEquals(NotificationSendStatus::CANCELED(), $deletedItem->get('send_status'));
    }

    public function testOnNotificationDeletedNodeRefNotExists(): void
    {
        $article = AlexaNotificationV1::create()
            ->set('app_ref', NodeRef::fromString('acme:alexa-app:test'))
            ->set('content_ref', NodeRef::fromString('acme:news:article:1234'));

        $nodeRef = NodeRef::fromNode($article);
        $event = NotificationDeletedV1::create()->set('node_ref', $nodeRef);

        $this->projector->onNotificationDeleted($event, $this->pbjx);
        $this->assertFalse($this->ncr->hasNode($nodeRef));
    }
}
