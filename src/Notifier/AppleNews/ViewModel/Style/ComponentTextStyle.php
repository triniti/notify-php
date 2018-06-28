<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;

/**
 * An Apple News Document ComponentTextStyle.
 */
class ComponentTextStyle extends TextStyle
{
    /** @var integer */
    public $firsLineIndent;

    /** @var boolean */
    public $hangingPunctuation;

    /** @var boolean */
    public $hyphenation;

    /** @var integer */
    public $lineHeight;

    /** @var integer */
    public $paragraphSpacingBefore;

    /** @var integer */
    public $paragraphSpacingAfter;

    /** @var TextShadow */
    public $textShadow;

    /** @var string */
    public $textAlignment;

    /** @var TextStyle */
    public $linkStyle;

    /** @var DropCapStyle */
    public $dropCapStyle;

    /**
     * @var string[] Valid Text Alignment values
     */
    private $validTextAlignment =
        [
            'left',
            'right',
            'center',
            'justified',
            'none',
        ];

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'firsLineIndent' => 'integer',
            'hangingPunctuation' => 'boolean',
            'hyphenation' => 'boolean',
            'lineHeight' => 'integer',
            'paragraphSpacingBefore' => 'integer',
            'paragraphSpacingAfter' => 'integer',
            'textShadow' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextShadow',
            'textAlignment' => 'string',
            'linkStyle' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle',
            'dropCapStyle' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\DropCapStyle',
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->textAlignment !== null) {
            Assertion::choice($this->textAlignment, $this->validTextAlignment, 'textAlignment does not have a valid value.');
        }

        parent::validateProperties();
    }
}
