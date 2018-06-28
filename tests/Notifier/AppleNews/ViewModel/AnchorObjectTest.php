<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class AnchorObjectTest extends AbstractPbjxTest
{
    /* @var AnchorObject */
    protected $anchorObject;

    public function setUp()
    {
        $this->anchorObject = new AnchorObject();
    }

    /**
     * @test init test
     */
    public function testCreateAnchorObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject', $this->anchorObject);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->anchorObject->targetComponentIdentifier = 'test';
        $expectedJson = '{"targetAnchorPosition":"bottom","originAnchorPosition":"bottom","targetComponentIdentifier":"test"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->anchorObject));
    }
}

