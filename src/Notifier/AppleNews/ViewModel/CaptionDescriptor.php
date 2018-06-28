<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle;

/**
 * Class CaptionDescriptor
 * An alternate object for creating captions that permits HTML and additonal features.
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/CaptionDescriptor.html#//apple_ref/doc/uid/TP40015408-CH91-SW1
 * @package Triniti\Notify\Notifier\AppleNews\ViewModel
 *
 * @property string $text Contains the raw caption text that needs to be displayed
 * @property ComponentTextStyle|String $textStyle Either an inline component text style object that contains styling information, or a string reference to a component text style object that is defined at the top level of the document in the componentTextStyles property.
 * @property string $format html, markdown, or none
 * @property TextStyle[] $inlineTextStyles Array of Text Style references for which the properties should be applied to ranges of the captions's text.
 *
 */
class CaptionDescriptor extends Base
{
    /** @var string */
    public $text;

    /** @var string|ComponentTextStyle */
    public $textStyle;

    /** @var string */
    public $format;

    /** @var TextStyle[] */
    public $inlineTextStyles;

    /** @var LinkAddition[] */
    public $additions;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'text'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'text' => 'string',
            'textStyle' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle',
            'format' => 'string',
            'inlineTextStyles' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle[]',
            'additions' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition[]'
        ));
    }
}
