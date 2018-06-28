<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStrokeStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TextStrokeStyleTest extends AbstractPbjxTest
{
    /* @var TextStrokeStyle */
    protected $textStrokeStyle;

    public function setUp()
    {
        $this->textStrokeStyle = new TextStrokeStyle();
    }

    /**
     * @test init test
     */
    public function testCreateTextStrokeStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStrokeStyle', $this->textStrokeStyle);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->textStrokeStyle->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->textStrokeStyle->color = 'red';
        $this->textStrokeStyle->width = 'string';
        $this->textStrokeStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->textStrokeStyle->color = 'red';
        $this->textStrokeStyle->width = 1;
        $expectedJson = '{"color":"red","width":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->textStrokeStyle));
    }

}



