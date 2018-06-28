<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Animation;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\AppearAnimation;
use Triniti\Tests\Notify\AbstractPbjxTest;

class AppearAnimationTest extends AbstractPbjxTest
{
    /* @var AppearAnimation */
    protected $appearAnimation;

    public function setUp()
    {
        $this->appearAnimation = new AppearAnimation();
    }

    /**
     * @test init test
     */
    public function testCreateAppearAnimation()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\AppearAnimation',
            $this->appearAnimation);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"appear"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->appearAnimation));
    }
}


