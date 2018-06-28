<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Offset;
use Triniti\Tests\Notify\AbstractPbjxTest;

class OffsetTest extends AbstractPbjxTest
{
    /* @var Offset */
    protected $offset;

    public function setUp()
    {
        $this->offset = new Offset();
    }

    /**
     * @test init test
     */
    public function testCreateOffset()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Offset', $this->offset);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->offset->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->offset->x = 1.1;
        $this->offset->y = 1;
        $this->offset->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->offset->x = 1.1;
        $this->offset->y = 1.2;
        $expectedJson = '{"x":1.1,"y":1.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->offset));
    }

}


