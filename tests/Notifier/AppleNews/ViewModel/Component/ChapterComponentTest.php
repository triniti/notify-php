<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ChapterComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ChapterComponentTest extends AbstractPbjxTest
{
    /* @var ChapterComponent */
    protected $chapterComponent;

    public function setUp()
    {
        $this->chapterComponent = new ChapterComponent();
    }

    /**
     * @test init test
     */
    public function testCreateChapterComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ChapterComponent',
            $this->chapterComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->chapterComponent->identifier = 'identifier';
        $expectedJson = '{"role":"chapter","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->chapterComponent));
    }
}



