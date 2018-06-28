<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\MapItemObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\MapSpanObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Map.html#//apple_ref/doc/uid/TP40015408-CH97-SW1
 *
 **/
class MapComponent extends BaseComponent
{
    /** @var string */
    public $role = 'map';

    /** @var float */
    public $latitude;

    /** @var float */
    public $longitude;

    /** @var string */
    public $accessibilityCaption;

    /** @var AnchorObject */
    public $anchor;

    /** @var ComponentAnimation */
    public $animation;

    /** @var Behavior */
    public $behavior;

    /** @var string */
    public $caption;

    /** @var MapItemObject[] */
    public $items = [];

    /** @var string */
    public $identifier;

    /** @var string */
    public $mapType;

    /** @var MapSpanObject */
    public $span;

    /** @var string|ComponentLayoutObject */
    public $layout;

    /** @var string|ComponentStyle */
    public $style;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role',
            'latitude',
            'longitude'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'latitude' => 'float',
            'longitude' => 'float',
            'accessibilityCaption' => 'string',
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'animation' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation',
            'behavior' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior',
            'caption' => 'string',
            'items' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\MapItemObject[]',
            'identifier' => 'string',
            'mapType' => 'string',
            'span' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\MapSpanObject',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle',
        ));
    }
}
