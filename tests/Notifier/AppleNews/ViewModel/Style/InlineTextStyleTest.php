<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class InlineTextStyleTest extends AbstractPbjxTest
{
    /* @var InlineTextStyle */
    protected $inlineTextStyle;

    public function setUp()
    {
        $this->inlineTextStyle = new InlineTextStyle();
    }

    /**
     * @test init test
     */
    public function testCreateInlineTextStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle', $this->inlineTextStyle);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->inlineTextStyle->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->inlineTextStyle->rangeLength = 10;
        $this->inlineTextStyle->rangeStart = 1;
        $textStyle = new TextStyle();
        $textStyle->fontSize = 'string';
        $this->inlineTextStyle->textStyle = $textStyle;
        $this->inlineTextStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->inlineTextStyle->rangeLength = 10;
        $this->inlineTextStyle->rangeStart = 1;
        $textStyle = new TextStyle();
        $textStyle->fontSize = 1;
        $this->inlineTextStyle->textStyle = $textStyle;
        $expectedJson = '{"rangeLength":10,"rangeStart":1,"textStyle":{"fontSize":1}}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->inlineTextStyle));
    }
}


