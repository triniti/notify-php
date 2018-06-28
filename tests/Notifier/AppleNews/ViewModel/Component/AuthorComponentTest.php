<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\AuthorComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class AuthorComponentTest extends AbstractPbjxTest
{
    /* @var AuthorComponent */
    protected $authorComponent;

    public function setUp()
    {
        $this->authorComponent = new AuthorComponent();
    }

    /**
     * @test init test
     */
    public function testCreateAuthorComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\AuthorComponent',
            $this->authorComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->authorComponent->validateProperties();
    }


    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->authorComponent->text = 'author';
        $this->authorComponent->format = 1;
        $this->authorComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->authorComponent->text = 'author';
        $this->authorComponent->format = 'html';
        $expectedJson = '{"role":"author","text":"author","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->authorComponent));
    }
}
