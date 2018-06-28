<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\InstagramComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class InstagramComponentTest extends AbstractPbjxTest
{
    /* @var InstagramComponent */
    protected $instagramComponent;

    public function setUp()
    {
        $this->instagramComponent = new InstagramComponent();
    }

    /**
     * @test init test
     */
    public function testCreateFacebookPostComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\InstagramComponent',
            $this->instagramComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->instagramComponent->validateProperties();
    }


    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->instagramComponent->URL = 'http://test.com';
        $this->instagramComponent->identifier = 1;
        $this->instagramComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->instagramComponent->URL = 'http://test.com';
        $this->instagramComponent->identifier = 'identifier';
        $expectedJson = '{"role":"instagram","URL":"http://test.com","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->instagramComponent));
    }
}





