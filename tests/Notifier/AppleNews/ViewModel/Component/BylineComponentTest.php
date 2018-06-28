<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BylineComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class BylineComponentTest extends AbstractPbjxTest
{
    /* @var BylineComponent */
    protected $bylineComponent;

    public function setUp()
    {
        $this->bylineComponent = new BylineComponent();
    }

    /**
     * @test init test
     */
    public function testCreateBylineComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BylineComponent',
            $this->bylineComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->bylineComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->bylineComponent->text = 'byline';
        $this->bylineComponent->format = 1;
        $this->bylineComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->bylineComponent->text = 'byline';
        $this->bylineComponent->format = 'html';
        $expectedJson = '{"role":"byline","text":"byline","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->bylineComponent));
    }
}

