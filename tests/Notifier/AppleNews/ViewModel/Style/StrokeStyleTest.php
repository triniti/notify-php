<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\StrokeStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class StrokeStyleTest extends AbstractPbjxTest
{
    /* @var StrokeStyle */
    protected $strokeStyle;

    public function setUp()
    {
        $this->strokeStyle = new StrokeStyle();
    }

    /**
     * @test init test
     */
    public function testCreateStrokeStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\StrokeStyle', $this->strokeStyle);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->strokeStyle->color = 'red';
        $this->strokeStyle->width = 1;
        $expectedJson = '{"color":"red","width":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->strokeStyle));
    }

}



