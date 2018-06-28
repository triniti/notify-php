<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\TitleComponent;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\DocumentObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\LayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle;
use Triniti\Tests\Notify\AbstractPbjxTest;

class DocumentObjectTest extends AbstractPbjxTest
{
    /* @var DocumentObject */
    protected $documentObject;

    public function setUp()
    {
        $this->documentObject = new DocumentObject();
    }

    /**
     * @test init test
     */
    public function testCreateDocumentObject()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\DocumentObject', $this->documentObject);
    }

    /**
     * @test testRequiredProperties
     *
     * @expectedException \Exception
     */
    public function testRequiredProperties()
    {
        $this->documentObject->validate();
    }

    /**
     * @test testInvalidProperties
     *
     * @expectedException \Exception
     */
    public function testInvalidProperties()
    {
        $this->documentObject->identifier = 'identifier';
        $this->documentObject->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $this->documentObject->layout = $layout;
        $titleComponent = new TitleComponent();
        $titleComponent->text = 1;
        $this->documentObject->components = [$titleComponent];

        $this->documentObject->validate();
    }

    /**
     * @test testInvalidProperties
     *
     * @param DocumentObject $documentObject
     * @dataProvider providerTestMissingGlobalSettings
     * @expectedException \Exception
     */
    public function testMissingGlobalSettings($documentObject)
    {
        $documentObject->validate();
    }

    /**
     * @test testValidationPasses
     *
     */
    public function testValidProperties()
    {
        $this->documentObject->identifier = 'identifier';
        $this->documentObject->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $this->documentObject->layout = $layout;
        $titleComponent = new TitleComponent();
        $titleComponent->text = 'title';
        $this->documentObject->components = [$titleComponent];
        $this->documentObject->validate();

        $this->assertEquals($titleComponent->text, $this->documentObject->components[0]->text);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->documentObject->identifier = 'identifier';
        $this->documentObject->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $this->documentObject->layout = $layout;
        $titleComponent = new TitleComponent();
        $titleComponent->text = 'text';
        $this->documentObject->components = [$titleComponent];

        $expectedJson = '{"version":"1.4","identifier":"identifier","language":"en","title":"title","layout":{"columns":1,"width":1},"components":[{"role":"title","text":"text"}]}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->documentObject));
    }

    /**
     * providerTestMissingGlobalSettings
     *
     * @return array
     */
    public function providerTestMissingGlobalSettings()
    {
        $docMissingComponentLayout = new DocumentObject();
        $docMissingComponentLayout->identifier = 'identifier';
        $docMissingComponentLayout->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $docMissingComponentLayout->layout = $layout;
        $titleComponent = new TitleComponent();
        $titleComponent->text = 'title';
        $titleComponent->layout = 'component-layout';
        $docMissingComponentLayout->components = [$titleComponent];
        $componentLayoutObject = new ComponentLayoutObject();
        $docMissingComponentLayout->addComponentLayout('component-layout-one', $componentLayoutObject);

        $docMissingComponentStyle = new DocumentObject();
        $docMissingComponentStyle->identifier = 'identifier';
        $docMissingComponentStyle->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $docMissingComponentStyle->layout = $layout;
        $titleComponent2 = new TitleComponent();
        $titleComponent2->text = 'title';
        $titleComponent2->style = 'component-style';
        $docMissingComponentStyle->components = [$titleComponent2];
        $componentStyle = new ComponentStyle();
        $docMissingComponentStyle->addComponentStyle('component-style-one', $componentStyle);

        $docMissingComponentTextStyle = new DocumentObject();
        $docMissingComponentTextStyle->identifier = 'identifier';
        $docMissingComponentTextStyle->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $docMissingComponentTextStyle->layout = $layout;
        $titleComponent2 = new TitleComponent();
        $titleComponent2->text = 'title';
        $titleComponent2->textStyle = 'component-textstyle';
        $docMissingComponentTextStyle->components = [$titleComponent2];
        $componentTextStyle = new ComponentTextStyle();
        $docMissingComponentTextStyle->addComponentTextStyle('component-textstyle-one', $componentTextStyle);

        $docMissinginlineTextStyle = new DocumentObject();
        $docMissinginlineTextStyle->identifier = 'identifier';
        $docMissinginlineTextStyle->title = 'title';
        $layout = new LayoutObject();
        $layout->columns = 1;
        $layout->width = 1;
        $docMissinginlineTextStyle->layout = $layout;
        $titleComponent2 = new TitleComponent();
        $titleComponent2->text = 'title';
        $inlineTextStyle = new InlineTextStyle();
        $inlineTextStyle->rangeLength = 2;
        $inlineTextStyle->rangeStart = 1;
        $inlineTextStyle->textStyle = 'inline-text-style';
        $titleComponent2->inlineTextStyles = [$inlineTextStyle];
        $docMissinginlineTextStyle->components = [$titleComponent2];
        $textStyle = new TextStyle();
        $docMissinginlineTextStyle->textStyles = ['inline-text-style2' => $textStyle];

        return [
            [$docMissingComponentLayout],
            [$docMissingComponentStyle],
            [$docMissingComponentTextStyle],
            [$docMissinginlineTextStyle]
        ];
    }
}
