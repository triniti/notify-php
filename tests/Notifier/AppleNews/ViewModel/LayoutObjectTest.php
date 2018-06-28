<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\LayoutObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class LayoutObjectTest extends AbstractPbjxTest
{
    /* @var LayoutObject */
    protected $layoutObject;

    public function setUp()
    {
        $this->layoutObject = new LayoutObject();
    }

    /**
     * @test init test
     */
    public function testCreateLayoutObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\LayoutObject', $this->layoutObject);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->layoutObject->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->layoutObject->columns = 1;
        $this->layoutObject->width = 'string';
        $this->layoutObject->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->layoutObject->columns = 1;
        $this->layoutObject->width = 2;
        $expectedJson = '{"columns":1,"width":2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->layoutObject));
    }
}

