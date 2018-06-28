<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PullQuoteComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class PullQuoteComponentTest extends AbstractPbjxTest
{
    /* @var PullQuoteComponent */
    protected $pullQuoteComponent;

    public function setUp()
    {
        $this->pullQuoteComponent = new PullQuoteComponent();
    }

    /**
     * @test init test
     */
    public function testCreatePullQuoteComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PullQuoteComponent',
            $this->pullQuoteComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->pullQuoteComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->pullQuoteComponent->text = 'pull';
        $this->pullQuoteComponent->format = 1;
        $this->pullQuoteComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->pullQuoteComponent->text = 'pull';
        $this->pullQuoteComponent->format = 'html';
        $expectedJson = '{"role":"pullquote","text":"pull","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->pullQuoteComponent));
    }
}
