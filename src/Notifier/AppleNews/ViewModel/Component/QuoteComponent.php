<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition;
use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Quote.html#//apple_ref/doc/uid/TP40015408-CH32-SW1
 *
 **/
class QuoteComponent extends BaseComponent
{
    /** @var string */
    public $role = 'quote';

    /** @var string */
    public $text;

    /** @var LinkAddition[] */
    public $additions = [];

    /** @var AnchorObject */
    public $anchor;

    /** @var ComponentAnimation */
    public $animation;

    /** @var Behavior */
    public $behavior;

    /** @var string */
    public $format;

    /** @var string */
    public $identifier;

    /** @var InlineTextStyle[] */
    public $inlineTextStyles;

    /** @var string|ComponentLayoutObject */
    public $layout;

    /** @var string|ComponentStyle */
    public $style;

    /** @var string|ComponentTextStyle */
    public $textStyle;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role',
            'text'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'text' => 'string',
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'additions' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Addition\LinkAddition[]',
            'animation' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation',
            'behavior' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior',
            'format' => 'string',
            'identifier' => 'string',
            'inlineTextStyles' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\InlineTextStyle[]',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle',
            'textStyle' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentTextStyle'
        ));
    }
}
