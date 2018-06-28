<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\MapItemObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MapItemObjectTest extends AbstractPbjxTest
{
    /* @var MapItemObject */
    protected $mapItemObject;

    public function setUp()
    {
        $this->mapItemObject = new MapItemObject();
    }

    /**
     * @test init test
     */
    public function testCreateLayoutObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\MapItemObject', $this->mapItemObject);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->mapItemObject->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->mapItemObject->latitude = 1.1;
        $this->mapItemObject->longitude = 2.2;
        $this->mapItemObject->caption = 2;
        $this->mapItemObject->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->mapItemObject->latitude = 1.1;
        $this->mapItemObject->longitude = 2.2;
        $this->mapItemObject->caption = 'caption';
        $expectedJson = '{"latitude":1.1,"longitude":2.2,"caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->mapItemObject));
    }
}

