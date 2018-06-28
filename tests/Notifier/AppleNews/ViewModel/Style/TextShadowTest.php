<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextShadow;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TextShadowTest extends AbstractPbjxTest
{
    /* @var TextShadow */
    protected $textShadow;

    public function setUp()
    {
        $this->textShadow = new TextShadow();
    }

    /**
     * @test init test
     */
    public function testCreateTextShadow()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextShadow', $this->textShadow);
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->textShadow->color = 'red';
        $this->textShadow->radius = 1.1;
        $this->textShadow->opacity = 1;
        $this->textShadow->validateProperties();
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->textShadow->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->textShadow->color = 'red';
        $this->textShadow->radius = 1.1;
        $this->textShadow->opacity = 1.2;
        $expectedJson = '{"color":"red","radius":1.1,"opacity":1.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->textShadow));
    }

}



