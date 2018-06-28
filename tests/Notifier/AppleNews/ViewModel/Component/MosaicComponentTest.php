<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MosaicComponent;
use Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class MosaicComponentTest extends AbstractPbjxTest
{
    /* @var MosaicComponent */
    protected $mosaicComponent;

    public function setUp()
    {
        $this->mosaicComponent = new MosaicComponent();
    }

    /**
     * @test init test
     */
    public function testCreateMosaicComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\MosaicComponent',
            $this->mosaicComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->mosaicComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $this->mosaicComponent->items = 'string';
        $this->mosaicComponent->identifier = 'identifier';
        $this->mosaicComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $this->mosaicComponent->items = [$galleryItem];
        $this->mosaicComponent->identifier = 'identifier';
        $expectedJson = '{"role":"mosaic","items":[{"URL":"http://www.test.com"}],"identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->mosaicComponent));
    }
}






