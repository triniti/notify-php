<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Scene;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Scene\ParallaxScaleHeader;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ParallaxScaleHeaderTest extends AbstractPbjxTest
{
    /* @var ParallaxScaleHeader */
    protected $parallaxScaleHeader;

    public function setUp()
    {
        $this->parallaxScaleHeader = new ParallaxScaleHeader();
    }

    /**
     * @test init test
     */
    public function testCreateParallaxScaleHeader()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Scene\ParallaxScaleHeader',
            $this->parallaxScaleHeader);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $expectedJson = '{"type":"parallax_scale"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->parallaxScaleHeader));
    }
}


