<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Scene;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Scene\FadingStickyHeader;
use Triniti\Tests\Notify\AbstractPbjxTest;

class FadingStickyHeaderTest extends AbstractPbjxTest
{
    /* @var FadingStickyHeader */
    protected $fadingStickyHeader;

    public function setUp()
    {
        $this->fadingStickyHeader = new FadingStickyHeader();
    }

    /**
     * @test init test
     */
    public function testCreateFadingStickyHeader()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Scene\FadingStickyHeader',
            $this->fadingStickyHeader);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"fading_sticky_header"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->fadingStickyHeader));
    }
}


