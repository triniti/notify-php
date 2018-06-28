<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MapComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MapComponentTest extends AbstractPbjxTest
{
    /* @var MapComponent */
    protected $mapComponent;

    public function setUp()
    {
        $this->mapComponent = new MapComponent();
    }

    /**
     * @test init test
     */
    public function testCreateFacebookPostComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MapComponent', $this->mapComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->mapComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->mapComponent->latitude = 1.1;
        $this->mapComponent->longitude = -1.1;
        $this->mapComponent->caption = 1;
        $this->mapComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->mapComponent->latitude = 1.1;
        $this->mapComponent->longitude = -1.1;
        $this->mapComponent->caption = 'caption';
        $expectedJson = '{"role":"map","latitude":1.1,"longitude":-1.1,"caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->mapComponent));
    }
}

