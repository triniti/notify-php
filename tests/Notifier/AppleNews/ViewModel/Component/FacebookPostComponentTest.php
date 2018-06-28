<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\FacebookPostComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class FacebookPostComponentTest extends AbstractPbjxTest
{
    /* @var FacebookPostComponent */
    protected $facebookPostComponent;

    public function setUp()
    {
        $this->facebookPostComponent = new FacebookPostComponent();
    }

    /**
     * @test init test
     */
    public function testCreateFacebookPostComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\FacebookPostComponent',
            $this->facebookPostComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->facebookPostComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->facebookPostComponent->URL = 'http://test.com';
        $this->facebookPostComponent->identifier = 1;
        $this->facebookPostComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->facebookPostComponent->URL = 'http://test.com';
        $this->facebookPostComponent->identifier = 'identifier';
        $expectedJson = '{"role":"facebook_post","URL":"http://test.com","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->facebookPostComponent));
    }
}




