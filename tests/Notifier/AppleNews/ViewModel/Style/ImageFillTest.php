<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ImageFill;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ImageFillTest extends AbstractPbjxTest
{
    /* @var ImageFill */
    protected $imageFill;

    public function setUp()
    {
        $this->imageFill = new ImageFill();
    }

    /**
     * @test init test
     */
    public function testCreateImageFill()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ImageFill', $this->imageFill);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->imageFill->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->imageFill->URL = 'http://www.test.com';
        $this->imageFill->attachment = 'test';
        $this->imageFill->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->imageFill->URL = 'http://www.test.com';
        $expectedJson = '{"type":"image","URL":"http://www.test.com"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->imageFill));
    }
}


