<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\AudioComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class AudioComponentTest extends AbstractPbjxTest
{
    /* @var AudioComponent */
    protected $audioComponent;

    public function setUp()
    {
        $this->audioComponent = new AudioComponent();
    }

    /**
     * @test init test
     */
    public function testCreateAudioComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\AudioComponent', $this->audioComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->audioComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->audioComponent->URL = 'http://test.com';
        $this->audioComponent->caption = 1;
        $this->audioComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->audioComponent->URL = 'http://test.com';
        $this->audioComponent->caption = 'caption';
        $expectedJson = '{"role":"audio","URL":"http://test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->audioComponent));
    }
}
