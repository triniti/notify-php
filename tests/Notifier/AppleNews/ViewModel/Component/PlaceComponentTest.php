<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PlaceComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class PlaceComponentTest extends AbstractPbjxTest
{
    /* @var PlaceComponent */
    protected $placeComponent;

    public function setUp()
    {
        $this->placeComponent = new PlaceComponent();
    }

    /**
     * @test init test
     */
    public function testCreatePlaceComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PlaceComponent', $this->placeComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->placeComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->placeComponent->latitude = 1.1;
        $this->placeComponent->longitude = -1.1;
        $this->placeComponent->caption = 1;
        $this->placeComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->placeComponent->latitude = 1.1;
        $this->placeComponent->longitude = -1.1;
        $this->placeComponent->caption = 'caption';
        $expectedJson = '{"role":"place","latitude":1.1,"longitude":-1.1,"caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->placeComponent));
    }
}

