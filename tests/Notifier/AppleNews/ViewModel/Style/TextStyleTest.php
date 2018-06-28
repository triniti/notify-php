<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TextStyleTest extends AbstractPbjxTest
{
    /* @var TextStyle */
    protected $textStyle;

    public function setUp()
    {
        $this->textStyle = new TextStyle();
    }

    /**
     * @test init test
     */
    public function testCreateTextStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle', $this->textStyle);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->textStyle->fontSize = 1;
        $this->textStyle->textColor = 'red';
        $expectedJson = '{"fontSize":1,"textColor":"red"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->textStyle));
    }

}
