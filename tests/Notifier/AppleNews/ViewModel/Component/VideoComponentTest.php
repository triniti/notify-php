<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\VideoComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class VideoComponentTest extends AbstractPbjxTest
{
    /* @var VideoComponent */
    protected $videoComponent;

    public function setUp()
    {
        $this->videoComponent = new VideoComponent();
    }

    /**
     * @test init test
     */
    public function testCreateVideoComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\VideoComponent', $this->videoComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->videoComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->videoComponent->URL = 'http://test.com';
        $this->videoComponent->identifier = 'identifier';
        $this->videoComponent->layout = 1;
        $this->videoComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->videoComponent->URL = 'http://test.com';
        $this->videoComponent->identifier = 'identifier';
        $expectedJson = '{"role":"video","URL":"http://test.com","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->videoComponent));
    }
}





