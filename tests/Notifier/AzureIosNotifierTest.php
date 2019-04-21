<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier;

use Acme\Schemas\Iam\Node\IosAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Node\IosNotificationV1;
use Acme\Schemas\Sys\Node\FlagsetV1;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Gdbots\Schemas\Pbjx\Enum\Code;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Triniti\Notify\Notifier;
use Triniti\Notify\Notifier\AzureIosNotifier;
use Triniti\Sys\Flags;
use Triniti\Tests\Notify\AbstractPbjxTest;

class AzureIosNotifierTest extends AbstractPbjxTest
{
    const CONNECTION_STRING = 'Endpoint=sb://endpoint/;SharedAccessKeyName=keyName;SharedAccessKey=keyValue';
    const HUB_NAME = 'ad-hoc';

    /** @var Flags */
    protected $flags;

    /** @var Key */
    protected $key;

    /** @var Notifier */
    protected $notifier;

    public function setUp()
    {
        parent::setUp();

        $flagset = FlagsetV1::fromArray(['_id' => 'test']);
        $this->ncr->putNode($flagset);
        $this->flags = new Flags($this->ncr, 'acme:flagset:test');
        $this->key = Key::createNewRandomKey();
        $this->notifier = new class($this->flags, $this->key) extends AzureIosNotifier
        {
            public function __construct(Flags $flags, Key $key)
            {
                parent::__construct($flags, $key);
                $this->parseConnectionString(AzureIosNotifierTest::CONNECTION_STRING);
            }

            public function parseConnectionString(string $connectionString): void
            {
                parent::parseConnectionString($connectionString);
            }

            /**
             * @return string
             */
            public function getEndpoint(): string
            {
                return $this->endpoint;
            }

            /**
             * @return string
             */
            public function getHubName(): string
            {
                return $this->hubName;
            }

            /**
             * @return string
             */
            public function getSasKeyName(): string
            {
                return $this->sasKeyName;
            }

            /**
             * @return string
             */
            public function getSasKeyValue(): string
            {
                return $this->sasKeyValue;
            }

            protected function getGuzzleClient(): GuzzleClient
            {
                $location = $this->getEndpoint().$this->getHubName().'/messages/123'.self::API_VERSION;
                $mock = new MockHandler(
                    [
                        new Response(201, ['Location' => $location]),
                    ]
                );
                $handler = HandlerStack::create($mock);

                return new GuzzleClient(
                    [
                        'base_uri' => $this->endpoint,
                        'handler'  => $handler,
                    ]
                );
            }

            /**
             * @param Flags $flags
             */
            public function setFlags(Flags $flags): void
            {
                $this->flags = $flags;
            }
        };
    }

    public function testParseConnectionString()
    {
        $this->assertEquals('https://endpoint/', $this->notifier->getEndpoint());
        $this->assertEquals('keyName', $this->notifier->getSasKeyName());
        $this->assertEquals('keyValue', $this->notifier->getSasKeyValue());
    }

    public function testSendWithIosFlagDisabled()
    {
        $flagset = FlagsetV1::fromArray(
            [
                '_id'      => 'ios',
                'booleans' => ['azure_ios_notifier_disabled' => true],
            ]
        );
        $this->ncr->putNode($flagset);
        $this->notifier->setFlags(new Flags($this->ncr, 'acme:flagset:ios'));
        $result = $this->notifier->send($this->getNotification(), $this->getApp(), $this->getContent());

        $this->assertFalse($result->get('ok'));
        $this->assertSame(Code::CANCELLED, $result->get('code'));
    }

    /**
     * @return IosNotificationV1
     */
    protected function getNotification(): IosNotificationV1
    {
        return IosNotificationV1::create()
            ->set('title', 'Nipsey Hussle Memorial Service Free Tickets Up for Grabs Tuesday')
            ->set('body', 'Body of the notification');
    }

    /**
     * @return IosAppV1
     */
    protected function getApp(): IosAppV1
    {
        return IosAppV1::create()
            ->set(
                'azure_notification_hub_connection',
                Crypto::encrypt(
                    self::CONNECTION_STRING,
                    $this->key
                )
            )
            ->set('azure_notification_hub_name', self::HUB_NAME);
    }

    /**
     * @return ArticleV1
     */
    protected function getContent(): ArticleV1
    {
        return ArticleV1::fromArray(['_id' => '5c9cc362-5a4b-11e9-9606-30342d323838']);
    }

    public function testSendWithOutAzureNotificationHubConnectionField()
    {
        $app = $this->getApp()->clear('azure_notification_hub_connection');
        $result = $this->notifier->send($this->getNotification(), $app, $this->getContent());
        $this->assertFalse($result->get('ok'));
        $this->assertSame(Code::INVALID_ARGUMENT, $result->get('code'));
    }

    public function testSendWithOutAzureNotificationHubNameField()
    {
        $app = $this->getApp()->clear('azure_notification_hub_name');
        $result = $this->notifier->send($this->getNotification(), $app, $this->getContent());
        $this->assertFalse($result->get('ok'));
        $this->assertSame(Code::INVALID_ARGUMENT, $result->get('code'));
    }

    /**
     * Notifications can be sent without a content-ref, payload uses notification body field
     */
    public function testSendWithoutContent()
    {
        $result = $this->notifier->send($this->getNotification(), $this->getApp());
        $this->assertTrue($result->get('ok'));
        $this->assertSame('123', $result->getFromMap('tags', 'azure_notification_id'));
    }

    public function testSend()
    {
        $result = $this->notifier->send($this->getNotification(), $this->getApp(), $this->getContent());
        $this->assertTrue($result->get('ok'));
        $this->assertSame('123', $result->getFromMap('tags', 'azure_notification_id'));
    }
}
