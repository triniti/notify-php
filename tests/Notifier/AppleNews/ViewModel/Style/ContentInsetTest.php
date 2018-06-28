<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ContentInset;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ContentInsetTest extends AbstractPbjxTest
{
    /* @var ContentInset */
    protected $contentInset;

    public function setUp()
    {
        $this->contentInset = new ContentInset();
    }

    /**
     * @test init test
     */
    public function testCreateContentInset()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ContentInset', $this->contentInset);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->contentInset->bottom = true;
        $this->contentInset->left = false;


        $expectedJson = '{"bottom":true,"left":false}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->contentInset));
    }
}

