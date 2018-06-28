<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ComponentTextStyleTest extends AbstractPbjxTest
{
    /* @var ComponentTextStyle */
    protected $componentTextStyle;

    public function setUp()
    {
        $this->componentTextStyle = new ComponentTextStyle();
    }

    /**
     * @test init test
     */
    public function testCreateComponentTextStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle',
            $this->componentTextStyle);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->componentTextStyle->textAlignment = 'center';
        $expectedJson = '{"textAlignment":"center"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->componentTextStyle));
    }
}

