<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Animation;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\FadeInAnimation;
use Triniti\Tests\Notify\AbstractPbjxTest;

class FadeInAnimationTest extends AbstractPbjxTest
{
    /* @var FadeInAnimation */
    protected $fadeInAnimation;

    public function setUp()
    {
        $this->fadeInAnimation = new FadeInAnimation();
    }

    /**
     * @test init test
     */
    public function testCreateFadeInAnimation()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\FadeInAnimation',
            $this->fadeInAnimation);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->fadeInAnimation->initialAlpha = 1.2;
        $expectedJson = '{"type":"fade_in","initialAlpha":1.2}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->fadeInAnimation));
    }
}


