<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\BackgroundMotion;
use Triniti\Tests\Notify\AbstractPbjxTest;

class BackgroundMotionTest extends AbstractPbjxTest
{
    /* @var BackgroundMotion */
    protected $backgroundMotion;

    public function setUp()
    {
        $this->backgroundMotion = new BackgroundMotion();
    }

    /**
     * @test init test
     */
    public function testCreateBackgroundMotion()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\BackgroundMotion',
            $this->backgroundMotion);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"background_motion"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->backgroundMotion));
    }
}


