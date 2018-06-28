<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DocumentStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class DocumentStyleTest extends AbstractPbjxTest
{
    /* @var DocumentStyle */
    protected $documentStyle;

    public function setUp()
    {
        $this->documentStyle = new DocumentStyle();
    }

    /**
     * @test init test
     */
    public function testCreateDocumentStyle()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DocumentStyle', $this->documentStyle);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->documentStyle->validateProperties();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->documentStyle->backgroundColor = 1;;
        $this->documentStyle->validateProperties();
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->documentStyle->backgroundColor = 'red';
        $expectedJson = '{"backgroundColor":"red"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->documentStyle));
    }
}


