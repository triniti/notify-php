<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\DividerComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class DividerComponentTest extends AbstractPbjxTest
{
    /* @var DividerComponent */
    protected $dividerComponent;

    public function setUp()
    {
        $this->dividerComponent = new DividerComponent();
    }

    /**
     * @test init test
     */
    public function testCreateDividerComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\DividerComponent',
            $this->dividerComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->dividerComponent->identifier = 'identifier';
        $expectedJson = '{"role":"divider","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->dividerComponent));
    }
}



