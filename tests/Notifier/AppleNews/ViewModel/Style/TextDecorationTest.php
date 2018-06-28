<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextDecoration;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TextDecorationTest extends AbstractPbjxTest
{
    /* @var TextDecoration */
    protected $textDecoration;

    public function setUp()
    {
        $this->textDecoration = new TextDecoration();
    }

    /**
     * @test init test
     */
    public function testCreateTextDecoration()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextDecoration', $this->textDecoration);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->textDecoration->color = 'red';
        $this->textDecoration->width = 1;
        $expectedJson = '{"color":"red","width":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->textDecoration));
    }

}



