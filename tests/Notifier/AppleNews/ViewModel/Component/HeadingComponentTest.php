<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\HeadingComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class HeadingComponentTest extends AbstractPbjxTest
{
    /* @var HeadingComponent */
    protected $headingComponent;

    public function setUp()
    {
        $this->headingComponent = new HeadingComponent();
    }

    /**
     * @test init test
     */
    public function testCreateHeadingComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\HeadingComponent',
            $this->headingComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->headingComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->headingComponent->text = 'heading';
        $this->headingComponent->format = 1;
        $this->headingComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->headingComponent->text = 'heading';
        $this->headingComponent->format = 'html';
        $expectedJson = '{"role":"heading","text":"heading","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->headingComponent));
    }
}
