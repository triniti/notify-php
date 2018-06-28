<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Animation;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ScaleFadeAnimation;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ScaleFadeAnimationTest extends AbstractPbjxTest
{
    /* @var ScaleFadeAnimation */
    protected $scaleFadeAnimation;

    public function setUp()
    {
        $this->scaleFadeAnimation = new ScaleFadeAnimation();
    }

    /**
     * @test init test
     */
    public function testCreateMoveInAnimation()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ScaleFadeAnimation',
            $this->scaleFadeAnimation);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->scaleFadeAnimation->initialAlpha = 1.2;
        $expectedJson = '{"type":"scale_fade","initialAlpha":1.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->scaleFadeAnimation));
    }
}


