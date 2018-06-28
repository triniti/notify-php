<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Motion;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MotionTest extends AbstractPbjxTest
{
    /* @var Motion */
    protected $motion;

    public function setUp()
    {
        $this->motion = new Motion();
    }

    /**
     * @test init test
     */
    public function testCreateMotion()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Motion', $this->motion);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"motion"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->motion));
    }
}


