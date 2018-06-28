<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\IllustratorComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class IllustratorComponentTest extends AbstractPbjxTest
{
    /* @var IllustratorComponent */
    protected $illustratorComponent;

    public function setUp()
    {
        $this->illustratorComponent = new IllustratorComponent();
    }

    /**
     * @test init test
     */
    public function testCreateIllustratorComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\IllustratorComponent',
            $this->illustratorComponent);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->illustratorComponent->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->illustratorComponent->text = 'illustrator';
        $this->illustratorComponent->format = 1;
        $this->illustratorComponent->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->illustratorComponent->text = 'illustrator';
        $this->illustratorComponent->format = 'html';
        $expectedJson = '{"role":"illustrator","text":"illustrator","format":"html"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->illustratorComponent));
    }
}
