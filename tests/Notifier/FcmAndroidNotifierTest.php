<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier;

use Acme\Schemas\Iam\Node\AndroidAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Node\AndroidNotificationV1;
use Acme\Schemas\Sys\Node\FlagsetV1;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Gdbots\Schemas\Pbjx\Enum\Code;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Triniti\Notify\Notifier;
use Triniti\Notify\Notifier\FcmAndroidNotifier;
use Triniti\Sys\Flags;
use Triniti\Tests\Notify\AbstractPbjxTest;

class FcmAndroidNotifierTest extends AbstractPbjxTest
{
    const FCM_API_KEY = 'XXX';

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
        $this->notifier = new class($this->flags, $this->key) extends FcmAndroidNotifier
        {
            protected function getGuzzleClient(): GuzzleClient
            {
                $mock = new MockHandler([
                    new Response(201, [], '{"message_id":"123"}'),
                ]);
                return new GuzzleClient(['handler' => HandlerStack::create($mock)]);
            }

            public function setFlags(Flags $flags): void
            {
                $this->flags = $flags;
            }
        };
    }

    public function testSendWithAndroidFlagDisabled()
    {
        $flagset = FlagsetV1::fromArray(
            [
                '_id'      => 'android',
                'booleans' => ['fcm_android_notifier_disabled' => true],
            ]
        );
        $this->ncr->putNode($flagset);
        $this->notifier->setFlags(new Flags($this->ncr, 'acme:flagset:android'));
        $result = $this->notifier->send($this->getNotification(), $this->getApp(), $this->getContent());

        $this->assertFalse($result->get('ok'), 'notifications are cancelled when flag is disabled');
        $this->assertSame(Code::CANCELLED, $result->get('code'), 'code must be set to cancelled when flag is disabled');
    }

    /**
     * Notifications can be sent without a content-ref, payload uses notification body field
     */
    public function testSendWithoutContent()
    {
        $result = $this->notifier->send($this->getNotificationWithTopics(), $this->getApp());
        $this->assertTrue($result->get('ok'), 'notifications can be sent without content');
        $this->assertSame(
            '123',
            $result->getFromMap('tags', 'fcm_message_id'),
            'fcm_message_id must match'
        );
    }

    public function testSendWithNotificationBody()
    {
        $result = $this->notifier->send(
            $this->getNotificationWithBody(),
            $this->getApp(),
            $this->getContent()
        );
        $this->assertTrue($result->get('ok'), 'notification must be sent successfully when FCM topics are set');
    }

    public function testSendWithTopics()
    {
        $result = $this->notifier->send(
            $this->getNotificationWithTopics(),
            $this->getApp(),
            $this->getContent()
        );
        $this->assertTrue($result->get('ok'), 'notification must be sent successfully when FCM topics are set');
    }

    /**
     * @return AndroidNotificationV1
     */
    protected function getNotification(): AndroidNotificationV1
    {
        return AndroidNotificationV1::create()
            ->set('title', 'Title of the notification');
    }

    /**
     * @return AndroidNotificationV1
     */
    protected function getNotificationWithBody(): AndroidNotificationV1
    {
        return AndroidNotificationV1::create()
            ->set('title', 'Title of the notification')
            ->set('body', 'Body of the notification');
    }

    /**
     * @return AndroidNotificationV1
     */
    protected function getNotificationWithTopics(): AndroidNotificationV1
    {
        return AndroidNotificationV1::create()
            ->set('title', 'Title of the notification')
            ->addToSet('fcm_topics', ['android-all']);
    }

    /**
     * @return AndroidAppV1
     */
    protected function getApp(): AndroidAppV1
    {
        return AndroidAppV1::create()
            ->set(
                'fcm_api_key',
                Crypto::encrypt(
                    self::FCM_API_KEY,
                    $this->key
                )
            );
    }

    /**
     * @return ArticleV1
     */
    protected function getContent(): ArticleV1
    {
        return ArticleV1::fromArray(
            [
                '_id'   => '5c9cc362-5a4b-11e9-9606-30342d323838',
                'title' => 'Article title',
            ]
        );
    }
}
