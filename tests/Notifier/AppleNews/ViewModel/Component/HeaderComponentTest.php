<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\HeaderComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class HeaderComponentTest extends AbstractPbjxTest
{
    /* @var HeaderComponent */
    protected $headerComponent;

    public function setUp()
    {
        $this->headerComponent = new HeaderComponent();
    }

    /**
     * @test init test
     */
    public function testCreateHeaderComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\HeaderComponent',
            $this->headerComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->headerComponent->identifier = 'identifier';
        $expectedJson = '{"role":"header","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->headerComponent));
    }
}
