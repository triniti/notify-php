<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\QuoteComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class QuoteComponentTest extends AbstractPbjxTest
{
    /* @var QuoteComponent */
    protected $quoteComponent;

    public function setUp()
    {
        $this->quoteComponent = new QuoteComponent();
    }

    /**
     * @test init test
     */
    public function testCreateQuoteComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\QuoteComponent', $this->quoteComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->quoteComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->quoteComponent->text = 'quote';
        $this->quoteComponent->format = 1;
        $this->quoteComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->quoteComponent->text = 'quote';
        $this->quoteComponent->format = 'html';
        $expectedJson = '{"role":"quote","text":"quote","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->quoteComponent));
    }
}
