<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ContainerComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ContainerComponentTest extends AbstractPbjxTest
{
    /* @var ContainerComponent */
    protected $containerComponent;

    public function setUp()
    {
        $this->containerComponent = new ContainerComponent();
    }

    /**
     * @test init test
     */
    public function testCreateContainerComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ContainerComponent',
            $this->containerComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->containerComponent->identifier = 'identifier';
        $expectedJson = '{"role":"container","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->containerComponent));
    }
}



