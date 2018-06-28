<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\CaptionComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class CaptionComponentTest extends AbstractPbjxTest
{
    /* @var CaptionComponent */
    protected $captionComponent;

    public function setUp()
    {
        $this->captionComponent = new CaptionComponent();
    }

    /**
     * @test init test
     */
    public function testCreateCaptionComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\CaptionComponent',
            $this->captionComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->captionComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->captionComponent->text = 'caption';
        $this->captionComponent->format = 1;
        $this->captionComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->captionComponent->text = 'caption';
        $this->captionComponent->format = 'html';
        $expectedJson = '{"role":"caption","text":"caption","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->captionComponent));
    }
}


