<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MusicComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MusicComponentTest extends AbstractPbjxTest
{
    /* @var MusicComponent */
    protected $musicComponent;

    public function setUp()
    {
        $this->musicComponent = new MusicComponent();
    }

    /**
     * @test init test
     */
    public function testCreateMusicComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MusicComponent', $this->musicComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->musicComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->musicComponent->URL = 'http://test.com';
        $this->musicComponent->caption = 1;
        $this->musicComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->musicComponent->URL = 'http://test.com';
        $this->musicComponent->caption = 'caption';
        $expectedJson = '{"role":"music","URL":"http://test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->musicComponent));
    }
}



