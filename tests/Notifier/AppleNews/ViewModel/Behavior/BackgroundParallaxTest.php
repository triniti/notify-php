<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\BackgroundParallax;
use Triniti\Tests\Notify\AbstractPbjxTest;

class BackgroundParallaxTest extends AbstractPbjxTest
{
    /* @var BackgroundParallax */
    protected $backgroundParallax;

    public function setUp()
    {
        $this->backgroundParallax = new BackgroundParallax();
    }

    /**
     * @test init test
     */
    public function testCreateBackgroundParallax()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\BackgroundParallax',
            $this->backgroundParallax);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"background_parallax"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->backgroundParallax));
    }
}


