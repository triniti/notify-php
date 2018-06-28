<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\DocumentMeta;
use Triniti\Tests\Notify\AbstractPbjxTest;

class DocumentMetaTest extends AbstractPbjxTest
{
    /* @var DocumentMeta */
    protected $documentMeta;

    public function setUp()
    {
        $this->documentMeta = new DocumentMeta();
    }

    /**
     * @test init test
     */
    public function testCreateDocumentMeta()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\DocumentMeta', $this->documentMeta);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->documentMeta->excerpt = 'excerpt';
        $expectedJson = '{"generatorIdentifier":"CFAppleNewsPlugin","generatorName":"Crowdfusion Apple News Plugin","generatorVersion":"0.9","excerpt":"excerpt"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->documentMeta));
    }
}
