<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ContentInset;

/**
 * Class ComponentLayoutObject
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/ComponentLayout.html#//apple_ref/doc/uid/TP40015408-CH56-SW1
 * @package Triniti\Notify\Notifier\AppleNews\ViewModel\Style
 *
 */

/**
 * For typehints
 * @var integer ComponentLayoutObject->colSpan
 * @var integer ComponentLayoutObject->columnStart
 * @var integer|ContentInset ComponentLayoutObject->contentInset
 * @var integer ComponentLayoutObject->horizontalContentAlignment
 * @var bool|string ComponentLayoutObject->ignoreDocumentGutter
 * @var bool|string ComponentLayoutObject->ignoreDocumentMargin
 * @var integer|MarginObject ComponentLayoutObject->margin
 * @var integer ComponentLayoutObject->maximumContentWidth
 * @var integer ComponentLayoutObject->minimumHeight
 **/
class ComponentLayoutObject extends Base
{
    /** @var integer */
    public $columnSpan;

    /** @var integer */
    public $columnStart;

    /** @var boolean|ContentInset */
    public $contentInset;

    /** @var string */
    public $horizontalContentAlignment;

    /** @var boolean|string */
    public $ignoreDocumentGutter;

    /** @var boolean|string */
    public $ignoreDocumentMargin;

    /** @var integer|MarginObject */
    public $margin;

    /** @var integer|string */
    public $maximumContentWidth;

    /** @var integer|string */
    public $minimumHeight;

    /**
     * @var string[] Valid horizontal content alignment values
     */
    private $validHorizontalContentAlignment =
        [
            'center',
            'left',
            'right'
        ];

    /**
     * @var string[] Valid ignore document gutter margin values
     */
    private $validIgnoreDocumentGutterMargin =
        [
            'none',
            'left',
            'right',
            'both'
        ];

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'columnSpan' => 'integer',
            'columnStart' => 'integer',
            'contentInset' => 'boolean|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ContentInset',
            'horizontalContentAlignment' => 'string',
            'ignoreDocumentGutter' => 'boolean|string',
            'ignoreDocumentMargin' => 'boolean|string',
            'margin' => 'integer|Triniti\Notify\Notifier\AppleNews\ViewModel\MarginObject',
            'maximumContentWidth' => 'integer|string',
            'minimumHeight' => 'integer|string'
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->horizontalContentAlignment !== null) {
            Assertion::choice($this->horizontalContentAlignment, $this->validHorizontalContentAlignment, 'horizontalContentAlignment does not have a valid value.');
        }

        if ($this->ignoreDocumentGutter !== null && is_string($this->ignoreDocumentGutter)) {
            Assertion::choice($this->ignoreDocumentGutter, $this->validIgnoreDocumentGutterMargin, 'ignoreDocumentGutter does not have a valid value.');
        }

        if ($this->ignoreDocumentMargin !== null && is_string($this->ignoreDocumentMargin)) {
            Assertion::choice($this->ignoreDocumentMargin, $this->validIgnoreDocumentGutterMargin, 'ignoreDocumentMargin does not have a valid value.');
        }

        parent::validateProperties();
    }
}
