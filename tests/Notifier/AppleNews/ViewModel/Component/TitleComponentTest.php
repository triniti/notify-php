<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\TitleComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class TitleComponentTest extends AbstractPbjxTest
{
    /* @var TitleComponent */
    protected $titleComponent;

    public function setUp()
    {
        $this->titleComponent = new TitleComponent();
    }

    /**
     * @test init test
     */
    public function testCreateTitleComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\TitleComponent', $this->titleComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->titleComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->titleComponent->text = 'title';
        $this->titleComponent->format = 1;
        $this->titleComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->titleComponent->text = 'title';
        $this->titleComponent->format = 'html';
        $expectedJson = '{"role":"title","text":"title","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->titleComponent));
    }
}

