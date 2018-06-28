<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PortraitComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class PortraitComponentTest extends AbstractPbjxTest
{
    /* @var PortraitComponent */
    protected $portraitComponent;

    public function setUp()
    {
        $this->portraitComponent = new PortraitComponent('http://www.test.com');
    }

    /**
     * @test init test
     */
    public function testCreatePortraitComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PortraitComponent',
            $this->portraitComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->portraitComponent->caption = 'caption';
        $expectedJson = '{"role":"portrait","URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->portraitComponent));
    }
}




