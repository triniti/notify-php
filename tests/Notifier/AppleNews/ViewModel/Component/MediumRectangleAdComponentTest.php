<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MediumRectangleAdComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MediumRectangleAdComponentTest extends AbstractPbjxTest
{
    /* @var MediumRectangleAdComponent */
    protected $mediumRectangleAdComponent;

    public function setUp()
    {
        $this->mediumRectangleAdComponent = new MediumRectangleAdComponent();
    }

    /**
     * @test init test
     */
    public function mediumRectangleAdComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MediumRectangleAdComponent',
            $this->mediumRectangleAdComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->mediumRectangleAdComponent->identifier = 'identifier';
        $expectedJson = '{"role":"medium_rectangle_advertisement","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->mediumRectangleAdComponent));
    }
}
