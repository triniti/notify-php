<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PhotographerComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class PhotographerComponentTest extends AbstractPbjxTest
{
    /* @var PhotographerComponent */
    protected $photographerComponent;

    public function setUp()
    {
        $this->photographerComponent = new PhotographerComponent();
    }

    /**
     * @test init test
     */
    public function testCreatePhotographerComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PhotographerComponent',
            $this->photographerComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->photographerComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->photographerComponent->text = 'photographer';
        $this->photographerComponent->format = 1;
        $this->photographerComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->photographerComponent->text = 'photographer';
        $this->photographerComponent->format = 'html';
        $expectedJson = '{"role":"photographer","text":"photographer","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->photographerComponent));
    }
}

