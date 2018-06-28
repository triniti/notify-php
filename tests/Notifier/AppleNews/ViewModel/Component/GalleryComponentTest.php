<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\GalleryComponent;
use Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\MapItemObject;
use Triniti\Tests\Notify\AbstractPbjxTest;

class GalleryComponentTest extends AbstractPbjxTest
{
    /* @var GalleryComponent */
    protected $galleryComponent;

    public function setUp()
    {
        $this->galleryComponent = new GalleryComponent();
    }

    /**
     * @test init test
     */
    public function testCreateGalleryComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\GalleryComponent',
            $this->galleryComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize() {
        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $this->galleryComponent->items = [$galleryItem];
        $this->galleryComponent->identifier = 'identifier';
        $expectedJson = '{"role":"gallery","items":[{"URL":"http://www.test.com"}],"identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->galleryComponent));
    }

    /**
     * @test testInvalidPropertyTypeArray
     * @expectedException \Exception
     *
     */
    public function testInvalidPropertyTypeArray() {
        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $galleryItem2 = new GalleryItemObject();
        $galleryItem2->URL = 'http://www.test.com';

        $galleryItem3 = new GalleryItemObject();
        $galleryItem3->URL = 'http://www.test.com';

        $galleryItem4 = new MapItemObject();
        $galleryItem4->latitude = 1;
        $galleryItem4->longitude = 1;

        $this->galleryComponent->items = [$galleryItem, $galleryItem2, $galleryItem3, $galleryItem4];
        $this->galleryComponent->identifier = 'identifier';
        $this->galleryComponent->layout = 'string';
        $this->galleryComponent->validateProperties();
    }

    /**
     * @test testInvalidPropertyType(
     * @expectedException \Exception
     *
     */
    public function testInvalidPropertyType() {

        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $galleryItem2 = new GalleryItemObject();
        $galleryItem2->URL = 'http://www.test.com';

        $galleryItem3 = new GalleryItemObject();
        $galleryItem3->URL = 'http://www.test.com';

        $this->galleryComponent->items = [$galleryItem, $galleryItem2, $galleryItem3];
        $this->galleryComponent->identifier = 'identifier';
        $this->galleryComponent->layout = 'string';
        $this->galleryComponent->animation = 'string';
        $this->galleryComponent->validateProperties();
    }

    /**
     * @test testInvalidPropertyMultipleTypes
     * @expectedException \Exception
     *
     */
    public function testInvalidPropertyMultipleTypes() {
        $galleryItem = new GalleryItemObject();
        $galleryItem->URL = 'http://www.test.com';

        $galleryItem2 = new GalleryItemObject();
        $galleryItem2->URL = 'http://www.test.com';

        $galleryItem3 = new GalleryItemObject();
        $galleryItem3->URL = 'http://www.test.com';

        $this->galleryComponent->items = [$galleryItem, $galleryItem2, $galleryItem3];
        $this->galleryComponent->identifier = 'identifier';
        $this->galleryComponent->layout = true;
        $this->galleryComponent->validateProperties();
    }

    /**
     * @test testMissingRequiredProperty
     * @expectedException \Exception
     *
     */
    public function testMissingRequiredProperty() {
        $this->galleryComponent->identifier = 'identifier';
        $this->galleryComponent->layout = 'string';
        $this->galleryComponent->validateProperties();
    }
}





