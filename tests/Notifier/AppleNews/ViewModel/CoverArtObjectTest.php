<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\CoverArtObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class CoverArtObjectTest extends AbstractPbjxTest
{
    /* @var CoverArtObject */
    protected $coverArtObject;

    public function setUp()
    {
        $this->coverArtObject = new CoverArtObject();
    }

    /**
     * @test init test
     */
    public function testCreateCoverArtObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\CoverArtObject', $this->coverArtObject);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->coverArtObject->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->coverArtObject->URL = 'http://www.test.com';
        $this->coverArtObject->accessibilityCaption = 1;
        $this->coverArtObject->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->coverArtObject->URL = 'http://www.test.com';
        $this->coverArtObject->accessibilityCaption = 'caption';
        $expectedJson = '{"type":"image","URL":"http://www.test.com","accessibilityCaption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->coverArtObject));
    }
}
