<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\EmbedWebVideoComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class EmbedWebVideoComponentTest extends AbstractPbjxTest
{
    /* @var EmbedWebVideoComponent */
    protected $embedWebVideoComponent;

    public function setUp()
    {
        $this->embedWebVideoComponent = new EmbedWebVideoComponent();
    }

    /**
     * @test init test
     */
    public function testCreateEmbedWebVideoComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\EmbedWebVideoComponent',
            $this->embedWebVideoComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->embedWebVideoComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->embedWebVideoComponent->URL = 'http://test.com';
        $this->embedWebVideoComponent->caption = 1;
        $this->embedWebVideoComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->embedWebVideoComponent->URL = 'http://test.com';
        $this->embedWebVideoComponent->caption = 'caption';
        $expectedJson = '{"role":"embedwebvideo","URL":"http://test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->embedWebVideoComponent));
    }
}



