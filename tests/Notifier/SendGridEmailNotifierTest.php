<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier;

use Acme\Schemas\Canvas\Block\TextBlockV1;
use Acme\Schemas\Iam\Node\EmailAppV1;
use Acme\Schemas\News\Node\ArticleV1;
use Acme\Schemas\Notify\Node\EmailNotificationV1;
use Acme\Schemas\Sys\Node\FlagsetV1;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Key;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Gdbots\Schemas\Pbjx\Enum\Code;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use Triniti\Notify\Notifier\SendGridEmailNotifier;
use Triniti\Sys\Flags;
use Triniti\Tests\Notify\AbstractPbjxTest;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class SendGridEmailNotifierTest extends AbstractPbjxTest
{
    /** @var Key */
    protected $key;

    /** @var Environment */
    protected $twig;

    /** @var SendGridEmailNotifier */
    private $notifier;

    public function setup()
    {
        parent::setup();

        $this->key = Key::createNewRandomKey();
        $loader = new FilesystemLoader(__DIR__ . '/../Fixtures/templates');
        $loader->addPath(realpath(__DIR__ . '/../Fixtures/templates/email_notifications'), 'email_notifications');
        $this->twig = new Environment($loader, ['debug' => true]);

        $flagset = FlagsetV1::fromArray(['_id' => 'test']);
        $this->ncr->putNode($flagset);
        $flags = new Flags($this->ncr, 'acme:flagset:test');

        $this->notifier = new class($flags, $this->key, $this->twig) extends SendGridEmailNotifier
        {
            protected function getGuzzleClient(): GuzzleClient
            {
                $mock = new MockHandler([
                    new Response(
                        201,
                        ['Content-Type' => 'application/json'],
                        json_encode(['id' => 123])
                    ),
                ]);
                $handler = HandlerStack::create($mock);
                return new GuzzleClient([
                    'base_uri' => 'https://api.com',
                    'handler'  => $handler,
                ]);
            }
        };
    }

    public function testSend()
    {
        $result = $this->notifier->send($this->getNotification(), $this->getApp(), $this->getContent());
        $this->assertTrue($result->get('ok'));
        $this->assertSame('123', $result->getFromMap('tags', 'sendgrid_campaign_id'));
    }

    public function testSendWithoutContent()
    {
        $result = $this->notifier->send($this->getNotification(), $this->getApp());
        $this->assertFalse($result->get('ok'));
        $this->assertEquals(Code::INVALID_ARGUMENT, $result->get('code'));
    }

    public function testSendWithMissingSender()
    {
        $notification = $this->getNotification();
        $notification->clear('sender');
        $result = $this->notifier->send($notification, $this->getApp(), $this->getContent());
        $this->assertFalse($result->get('ok'));
        $this->assertEquals(Code::INVALID_ARGUMENT, $result->get('code'));
    }

    /**
     * @return EmailNotificationV1
     */
    protected function getNotification(): EmailNotificationV1
    {
        return EmailNotificationV1::create()
            ->set('status', NodeStatus::PUBLISHED())
            ->set('title', 'Lorem ipsum dolor sit amet')
            ->set('sender', 'simple@example.com')
            ->set('template', 'breaking-news')
            ->set('subject', 'Lorem ipsum dolor sit amet')
            ->addToSet('lists', ['demo']);
    }

    /**
     * @return EmailAppV1
     */
    protected function getApp(): EmailAppV1
    {
        return EmailAppV1::create()
            ->set('status', NodeStatus::PUBLISHED())
            ->set('title', 'SendGrid Email')
            ->set('sendgrid_api_key', Crypto::encrypt('XXX', $this->key))
            ->set('sendgrid_suppression_group_id', 6775)
            ->addToMap('sendgrid_senders', 'hello@example.com', 319136)
            ->addToMap('sendgrid_lists', 'demo', 4946985);
    }

    /**
     * @return ArticleV1
     */
    protected function getContent(): ArticleV1
    {
        return ArticleV1::create()
            ->set('status', NodeStatus::PUBLISHED())
            ->set('slug', '2018/08/08/lorem-ipsum')
            ->addToList('blocks', [
                TextBlockV1::create()
                    ->set('text', '<p>this is a block from unit test</p>'),
            ]);
    }
}
