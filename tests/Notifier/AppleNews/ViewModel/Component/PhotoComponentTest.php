<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PhotoComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class PhotoComponentTest extends AbstractPbjxTest
{
    /* @var PhotoComponent */
    protected $photoComponent;

    public function setUp()
    {
        $this->photoComponent = new PhotoComponent('http://www.test.com');
    }

    /**
     * @test init test
     */
    public function testCreatePhotoComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\PhotoComponent', $this->photoComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->photoComponent->caption = 'caption';
        $expectedJson = '{"role":"photo","URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->photoComponent));
    }
}





