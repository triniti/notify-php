<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\MapSpanObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MapSpanObjectTest extends AbstractPbjxTest
{
    /* @var MapSpanObject */
    protected $mapSpanObject;

    public function setUp()
    {
        $this->mapSpanObject = new MapSpanObject();
    }

    /**
     * @test init test
     */
    public function testCreateLayoutObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\MapSpanObject', $this->mapSpanObject);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->mapSpanObject->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->mapSpanObject->latitudeDelta = 1.1;
        $this->mapSpanObject->longitudeDelta = 'string';
        $this->mapSpanObject->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->mapSpanObject->latitudeDelta = 1.1;
        $this->mapSpanObject->longitudeDelta = 2.2;
        $expectedJson = '{"latitudeDelta":1.1,"longitudeDelta":2.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->mapSpanObject));
    }
}

