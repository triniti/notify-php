<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\IntroComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class IntroComponentTest extends AbstractPbjxTest
{
    /* @var IntroComponent */
    protected $introComponent;

    public function setUp()
    {
        $this->introComponent = new IntroComponent();
    }

    /**
     * @test init test
     */
    public function testCreateFacebookPostComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\IntroComponent', $this->introComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->introComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->introComponent->URL = 'http://test.com';
        $this->introComponent->identifier = 1;
        $this->introComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->introComponent->text = 'intro';
        $this->introComponent->identifier = 'identifier';
        $expectedJson = '{"role":"intro","text":"intro","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->introComponent));
    }
}





