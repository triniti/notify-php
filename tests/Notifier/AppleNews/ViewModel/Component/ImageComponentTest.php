<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ImageComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class ImageComponentTest extends AbstractPbjxTest
{
    /* @var ImageComponent */
    protected $imageComponent;

    public function setUp()
    {
        $this->imageComponent = new ImageComponent('http://www.test.com');
    }

    /**
     * @test init test
     */
    public function testCreateImageComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\ImageComponent', $this->imageComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->imageComponent->caption = 'caption';
        $expectedJson = '{"role":"logo","URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->imageComponent));
    }
}





