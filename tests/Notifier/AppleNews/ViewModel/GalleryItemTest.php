<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class GalleryItemTest extends AbstractPbjxTest
{
    /* @var GalleryItemObject */
    protected $galleryItem;

    public function setUp()
    {
        $this->galleryItem = new GalleryItemObject();
    }

    /**
     * @test init test
     */
    public function testCreateGalleryItemObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject', $this->galleryItem);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->galleryItem->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->galleryItem->URL = 'http://www.test.com';
        $this->galleryItem->caption = 'caption';
        $this->galleryItem->explicitContent = 'caption';
        $this->galleryItem->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->galleryItem->URL = 'http://www.test.com';
        $this->galleryItem->caption = 'caption';
        $expectedJson = '{"URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->galleryItem));
    }
}

