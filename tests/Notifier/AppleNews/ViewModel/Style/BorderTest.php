<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Border;
use Triniti\Tests\Notify\AbstractPbjxTest;

class BorderTest extends AbstractPbjxTest
{
    /* @var Border */
    protected $border;

    public function setUp()
    {
        $this->border = new Border();
    }

    /**
     * @test init test
     */
    public function testCreateBorder()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Border', $this->border);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->border->top = 1;
        $expectedJson = '{"top":1}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->border));
    }
}

