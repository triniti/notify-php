<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\SectionComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class SectionComponentTest extends AbstractPbjxTest
{
    /* @var SectionComponent */
    protected $sectionComponent;

    public function setUp()
    {
        $this->sectionComponent = new SectionComponent();
    }

    /**
     * @test init test
     */
    public function testCreateSectionComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\SectionComponent',
            $this->sectionComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->sectionComponent->identifier = 'identifier';
        $expectedJson = '{"role":"section","identifier":"identifier"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->sectionComponent));
    }
}



