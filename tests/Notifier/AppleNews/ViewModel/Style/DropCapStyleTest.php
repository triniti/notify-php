<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DropCapStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class DropCapStyleTest extends AbstractPbjxTest
{
    /* @var DropCapStyle */
    protected $dropCapStyle;

    public function setUp()
    {
        $this->dropCapStyle = new DropCapStyle();
    }

    /**
     * @test init test
     */
    public function testCreateDropCapStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DropCapStyle', $this->dropCapStyle);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->dropCapStyle->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->dropCapStyle->numberOfLines = 3;
        $this->dropCapStyle->padding = 'string';
        $this->dropCapStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->dropCapStyle->numberOfLines = 3;
        $this->dropCapStyle->padding = 1;
        $expectedJson = '{"numberOfLines":3,"padding":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->dropCapStyle));
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerializeEdges()
    {
        $this->dropCapStyle->numberOfLines = 11;
        $this->dropCapStyle->padding = 1;
        $expectedJson = '{"numberOfLines":10,"padding":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->dropCapStyle));

        $this->dropCapStyle->numberOfLines = -2;
        $expectedJson = '{"numberOfLines":2,"padding":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->dropCapStyle));
    }
}


