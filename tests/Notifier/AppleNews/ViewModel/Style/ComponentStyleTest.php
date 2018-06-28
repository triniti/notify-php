<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ComponentStyleTest extends AbstractPbjxTest
{
    /* @var ComponentStyle */
    protected $componentStyle;

    public function setUp()
    {
        $this->componentStyle = new ComponentStyle();
    }

    /**
     * @test init test
     */
    public function testCreateComponentStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle', $this->componentStyle);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->componentStyle->backgroundColor = 'red';
        $expectedJson = '{"backgroundColor":"red"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->componentStyle));
    }
}

