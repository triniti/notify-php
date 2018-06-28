<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document TextStyle.
 */
class TextStyle extends Base
{
    /** @var string */
    public $fontName;

    /** @var integer */
    public $fontSize;

    /** @var string */
    public $textColor;

    /** @var string */
    public $backgroundColor;

    /** @var string */
    public $verticalAlignment;

    /** @var TextShadow */
    public $textShadow;

    /** @var boolean|TextDecoration */
    public $underline;

    /** @var boolean|TextDecoration */
    public $strikethrough;

    /** @var float */
    public $tracking;

    /** @var TextStrokeStyle */
    public $stroke;

    /** @var ListItemStyle */
    public $orderedListItems;

    /** @var ListItemStyle */
    public $unorderedListItems;

    /**
     * @var string[] Valid vertical alignment values
     */
    private $validVerticalAlignment =
        [
            'superscript',
            'subscript',
            'baseline'
        ];

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'fontName' => 'string',
            'fontSize' => 'integer',
            'textColor' => 'string',
            'backgroundColor' => 'string',
            'verticalAlignment' => 'string',
            'textShadow' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextShadow',
            'underline' => 'boolean|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextDecoration',
            'strikethrough' => 'boolean|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextDecoration',
            'tracking' => 'float',
            'stroke' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStrokeStyle',
            'orderedListItems' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ListItemStyle',
            'unorderedListItems' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ListItemStyle',
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->verticalAlignment !== null) {
            Assertion::choice($this->verticalAlignment, $this->validVerticalAlignment, 'verticalAlignment does not have a valid value.');
        }

        parent::validateProperties();
    }
}
