<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\TweetComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TweetComponentTest extends AbstractPbjxTest
{
    /* @var TweetComponent */
    protected $tweetComponent;

    public function setUp()
    {
        $this->tweetComponent = new TweetComponent();
    }

    /**
     * @test init test
     */
    public function testCreateTweetComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\TweetComponent', $this->tweetComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->tweetComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->tweetComponent->URL = 'http://test.com';
        $this->tweetComponent->identifier = 1;
        $this->tweetComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->tweetComponent->URL = 'http://test.com';
        $this->tweetComponent->identifier = 'identifier';
        $expectedJson = '{"role":"tweet","URL":"http://test.com","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->tweetComponent));
    }
}





