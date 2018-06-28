<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Parallax;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ParallaxTest extends AbstractPbjxTest
{
    /* @var Parallax */
    protected $parallax;

    public function setUp()
    {
        $this->parallax = new Parallax();
    }

    /**
     * @test init test
     */
    public function testCreateParallax()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Parallax', $this->parallax);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"parallax"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->parallax));
    }
}


