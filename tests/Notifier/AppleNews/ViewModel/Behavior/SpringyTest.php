<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Springy;
use Triniti\Tests\Notify\AbstractPbjxTest;

class SpringyTest extends AbstractPbjxTest
{
    /* @var Springy */
    protected $springy;

    public function setUp()
    {
        $this->springy = new Springy();
    }

    /**
     * @test init test
     */
    public function testCreateSpringy()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Springy', $this->springy);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"springy"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->springy));
    }
}


