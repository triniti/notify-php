<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\StrokeStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Divider.html#//apple_ref/doc/uid/TP40015408-CH14-SW1
 *
 **/
class DividerComponent extends BaseComponent
{
    /** @var string */
    public $role = 'divider';

    /** @var AnchorObject */
    public $anchor;

    /** @var ComponentAnimation */
    public $animation;

    /** @var Behavior */
    public $behavior;

    /** @var string */
    public $identifier;

    /** @var string|ComponentLayoutObject */
    public $layout;

    /** @var StrokeStyle */
    public $stroke;

    /** @var string|ComponentStyle */
    public $style;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'animation' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation',
            'behavior' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior',
            'identifier' => 'string',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'stroke' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\StrokeStyle',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle',
        ));
    }
}
