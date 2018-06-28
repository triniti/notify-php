<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Component\FigureComponent;
use Triniti\Tests\Notify\AbstractPbjxTest;

class FigureComponentTest extends AbstractPbjxTest
{
    /* @var FigureComponent */
    protected $figureComponent;

    public function setUp()
    {
        $this->figureComponent = new FigureComponent('http://www.test.com');
    }

    /**
     * @test init test
     */
    public function testCreateFigureComponent()
    {
        $this->assertInstanceOf('Triniti\Notify\Notifier\AppleNews\ViewModel\Component\FigureComponent',
            $this->figureComponent);
    }

    /**
     * @test testJsonSerialize
     *
     */
    public function testJsonSerialize()
    {
        $this->figureComponent->caption = 'caption';
        $expectedJson = '{"role":"logo","URL":"http://www.test.com","caption":"caption"}';
        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($this->figureComponent));
    }
}




