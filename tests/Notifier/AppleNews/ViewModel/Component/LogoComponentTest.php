<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\LogoComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class LogoComponentTest extends AbstractPbjxTest
{
    /* @var LogoComponent */
    protected $logoComponent;

    public function setUp()
    {
        $this->logoComponent = new LogoComponent('http://www.test.com');
    }

    /**
     * @test init test
     */
    public function testCreateLogoComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\LogoComponent', $this->logoComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->logoComponent->caption = 'caption';
        $expectedJson = '{"role":"photo","URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->logoComponent));
    }
}




