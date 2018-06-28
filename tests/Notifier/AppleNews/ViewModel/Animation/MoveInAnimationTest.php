<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Animation;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\MoveInAnimation;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MoveInAnimationTest extends AbstractPbjxTest
{
    /* @var MoveInAnimation */
    protected $moveInAnimation;

    public function setUp()
    {
        $this->moveInAnimation = new MoveInAnimation();
    }

    /**
     * @test init test
     */
    public function testCreateMoveInAnimation()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\MoveInAnimation',
            $this->moveInAnimation);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->moveInAnimation->preferredStartingPosition = 1.2;
        $expectedJson = '{"type":"move_in","preferredStartingPosition":1.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->moveInAnimation));
    }
}


