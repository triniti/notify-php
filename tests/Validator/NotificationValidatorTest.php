<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Validator;

use Acme\Schemas\Notify\Command\UpdateNotificationV1;
use Acme\Schemas\Notify\Node\IosNotificationV1;
use Acme\Schemas\Notify\Request\GetNotificationRequestV1;
use Gdbots\Pbjx\Event\PbjxEvent;
use Gdbots\Schemas\Ncr\NodeRef;
use Triniti\Notify\GetNotificationRequestHandler;
use Triniti\Notify\Validator\NotificationValidator;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;
use Triniti\Tests\Notify\AbstractPbjxTest;

class NotificationValidatorTest extends AbstractPbjxTest
{
    public function setup()
    {
        parent::setup();
        // prepare request handlers that this test case requires
        PbjxEvent::setPbjx($this->pbjx);
        $this->locator->registerRequestHandler(
            GetNotificationRequestV1::schema()->getCurie(),
            new GetNotificationRequestHandler($this->ncr)
        );
    }

    /**
     * @expectedException \Triniti\Notify\Exception\NotificationAlreadySent
     */
    public function testValidateUpdateNotificationThatAlreadySent(): void
    {
        $command = UpdateNotificationV1::create();
        $existingNode = IosNotificationV1::fromArray([
            'app_ref'     => NodeRef::fromString('acme:ios-app:1234'),
            'content_ref' => NodeRef::fromString('acme:artilce:1234'),
        ]);
        $existingNode
            ->set('send_status', NotificationSendStatus::SENT())
            ->set('sent_at', new \DateTime('2018-01-01'));

        $newNode = IosNotificationV1::fromArray([
            '_id'         => $existingNode->get('_id'),
            'app_ref'     => NodeRef::fromString('acme:ios-app:1234'),
            'content_ref' => NodeRef::fromString('acme:artilce:updated'),
        ]);

        $this->ncr->putNode($existingNode);
        $command->set('node_ref', NodeRef::fromNode($existingNode))->set('old_node', $existingNode)->set('new_node', $newNode);

        $validator = new NotificationValidator();
        $pbjxEvent = new PbjxEvent($command);
        $validator->validateUpdateNotification($pbjxEvent);
    }

    public function testValidateCreateUserThatHasNotBeenSent(): void
    {
        $command = UpdateNotificationV1::create();
        $existingNode = IosNotificationV1::fromArray([
            'app_ref'     => NodeRef::fromString('acme:ios-app:1234'),
            'content_ref' => NodeRef::fromString('acme:artilce:1234'),
            'send_status' => NotificationSendStatus::SCHEDULED(),
            'body'        => 'a notification',
        ]);

        $newNode = IosNotificationV1::fromArray([
            '_id'         => $existingNode->get('_id'),
            'app_ref'     => NodeRef::fromString('acme:ios-app:1234'),
            'content_ref' => NodeRef::fromString('acme:artilce:updated'),
            'body'        => 'a message update',
        ]);

        $this->ncr->putNode($existingNode);
        $command->set('node_ref', NodeRef::fromNode($existingNode))->set('old_node', $existingNode)->set('new_node', $newNode);

        $validator = new NotificationValidator();
        $pbjxEvent = new PbjxEvent($command);
        $validator->validateUpdateNotification($pbjxEvent);

        // if it gets here it's a pass
        $this->assertTrue(true);
    }
}
