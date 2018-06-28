<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BodyComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class BodyComponentTest extends AbstractPbjxTest
{
    /* @var BodyComponent */
    protected $bodyComponent;

    public function setUp()
    {
        $this->bodyComponent = new BodyComponent();
    }

    /**
     * @test init test
     */
    public function testCreateBodyComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\BodyComponent', $this->bodyComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->bodyComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->bodyComponent->text = 'body';
        $this->bodyComponent->format = 1;
        $this->bodyComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->bodyComponent->text = 'body';
        $this->bodyComponent->format = 'html';
        $expectedJson = '{"role":"body","text":"body","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->bodyComponent));
    }
}
