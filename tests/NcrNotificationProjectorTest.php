<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

// todo: make sure this has everything from NcrNotificationProjectorTest
use Acme\Schemas\Notify\Command\CreateNotificationV1;
use Acme\Schemas\Notify\Event\NotificationFailedV1;
use Acme\Schemas\Notify\Node\IosNotificationV1;
use Triniti\Notify\NcrNotificationProjector;
use Triniti\Notify\NotificationAggregate;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Schemas\Notify\Event\NotificationSentV1;
use Triniti\Schemas\Notify\NotifierResultV1;

final class NcrNotificationProjectorTestOld extends AbstractPbjxTest
{
    private NcrNotificationProjector $projector;
    private MockNcrSearch $ncrSearch;

    protected function setup(): void
    {
        parent::setup();
        $this->ncrSearch = new MockNcrSearch();
        $this->projector = new NcrNotificationProjector($this->ncr, $this->ncrSearch);
    }

    public function testOnNotificationFailed(): void
    {
        $node = IosNotificationV1::create()->set('sent_at', new \DateTime('NOW'));
        $nodeRef = $node->generateNodeRef();
        $aggregate = NotificationAggregate::fromNode($node, $this->pbjx);
        $aggregate->createNode(CreateNotificationV1::create()->set('node', $node));
        $aggregate->commit();
        $notifierResult = NotifierResultV1::create();
        $event = NotificationFailedV1::create()
            ->set('node_ref', $nodeRef)
            ->set('notifier_result', $notifierResult);
        $this->projector->onNotificationFailed($event, $this->pbjx);

        $actualNode = $this->ncr->getNode($nodeRef);
        $this->assertFalse($actualNode->has('sent_at'));
        $this->assertTrue($notifierResult->equals($actualNode->get('notifier_result')));
        $this->assertTrue(NotificationSendStatus::FAILED()->equals($actualNode->get('send_status')));
    }

    public function testOnNotificationSent(): void
    {
        $node = IosNotificationV1::create()->set('sent_at', new \DateTime('NOW'));
        $nodeRef = $node->generateNodeRef();
        $aggregate = NotificationAggregate::fromNode($node, $this->pbjx);
        $aggregate->createNode(CreateNotificationV1::create()->set('node', $node));
        $aggregate->commit();
        $notifierResult = NotifierResultV1::create();
        $event = NotificationSentV1::create()
            ->set('node_ref', $nodeRef)
            ->set('notifier_result', $notifierResult);
        $this->projector->onNotificationSent($event, $this->pbjx);

        $actualNode = $this->ncr->getNode($nodeRef);
        $this->assertSame($event->get('occurred_at')->toDateTime()->getTimeStamp(), $actualNode->get('sent_at')->getTimeStamp());
        $this->assertTrue(NotificationSendStatus::SENT()->equals($actualNode->get('send_status')));
        $this->assertTrue($notifierResult->equals($actualNode->get('notifier_result')));
    }
}
