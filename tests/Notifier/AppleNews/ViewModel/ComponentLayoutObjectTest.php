<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ComponentLayoutObjectTest extends AbstractPbjxTest
{
    /* @var ComponentLayoutObject */
    protected $componentLayoutObject;

    public function setUp()
    {
        $this->componentLayoutObject = new ComponentLayoutObject();
    }

    /**
     * @test init test
     */
    public function testCreateComponentLayoutObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            $this->componentLayoutObject);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->componentLayoutObject->columnSpan = 1;
        $this->componentLayoutObject->columnStart = 2;
        $expectedJson = '{"columnSpan":1,"columnStart":2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->componentLayoutObject));
    }
}
