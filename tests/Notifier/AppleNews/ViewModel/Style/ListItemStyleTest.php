<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ListItemStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ListItemStyleTest extends AbstractPbjxTest
{
    /* @var ListItemStyle */
    protected $listItemStyle;

    public function setUp()
    {
        $this->listItemStyle = new ListItemStyle();
    }

    /**
     * @test init test
     */
    public function testCreateListItemStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ListItemStyle', $this->listItemStyle);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->listItemStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->listItemStyle->type = 'bullet';
        $this->listItemStyle->character = 'character';
        $expectedJson = '{"type":"bullet","character":"character"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->listItemStyle));
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->listItemStyle->type = 'bullet';
        $this->listItemStyle->character = 1;
        $this->listItemStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     * @expectedException \Exception
     */
    public function testInvalidType()
    {
        $this->listItemStyle->type = 'test';
        $this->listItemStyle->character = 'character';
        $this->listItemStyle->validateProperties();
    }
}


