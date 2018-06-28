<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\VideoFill;
use Triniti\Tests\Notify\AbstractPbjxTest;

class VideoFillTest extends AbstractPbjxTest
{
    /* @var VideoFill */
    protected $videoFill;

    public function setUp()
    {
        $this->videoFill = new VideoFill();
    }

    /**
     * @test init test
     */
    public function testCreateVideoFill()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\VideoFill', $this->videoFill);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->videoFill->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->videoFill->URL = 'http://www.test.com';
        $this->videoFill->stillURL = 1;
        $this->videoFill->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->videoFill->URL = 'http://www.test.com';
        $this->videoFill->stillURL = 'http://www.test.com';
        $expectedJson = '{"type":"video","URL":"http://www.test.com","stillURL":"http://www.test.com"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->videoFill));
    }
}


