<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Addition;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition;
use Triniti\Tests\Notify\AbstractPbjxTest;

class LinkAdditionTest extends AbstractPbjxTest
{
    /* @var LinkAddition */
    protected $linkAddition;

    public function setUp()
    {
        $this->linkAddition = new LinkAddition();
    }

    /**
     * @test init test
     */
    public function testCreateLinkAddition()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition', $this->linkAddition);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->linkAddition->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->linkAddition->rangeLength = 2;
        $this->linkAddition->rangeStart = 'string';
        $this->linkAddition->URL = 'http://www.test.com';
        $this->linkAddition->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->linkAddition->rangeLength = 2;
        $this->linkAddition->rangeStart = 1;
        $this->linkAddition->URL = 'http://www.test.com';
        $expectedJson = '{"type":"link","rangeLength":2,"rangeStart":1,"URL":"http://www.test.com"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->linkAddition));
    }
}



