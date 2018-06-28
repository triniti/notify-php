<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/EmbedVideo.html#//apple_ref/doc/uid/TP40015408-CH15-SW1
 *
 **/
class EmbedWebVideoComponent extends BaseComponent
{
    /** @var string */
    public $role = "embedwebvideo";

    /** @var string */
    public $URL;

    /** @var string */
    public $accessibilityCaption;

    /** @var AnchorObject */
    public $anchor;

    /** @var ComponentAnimation */
    public $animation;

    /** @var float */
    public $aspectRatio;

    /** @var Behavior */
    public $behavior;

    /** @var string */
    public $caption;

    /** @var boolean */
    public $explicitContent;

    /** @var string */
    public $identifier;

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
            'URL'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'URL' => 'string',
            'accessibilityCaption' => 'string',
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'animation' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation',
            'aspectRatio' => 'float',
            'behavior' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior',
            'caption' => 'string',
            'explicitContent' => 'boolean',
            'identifier' => 'string',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle'
        ));
    }
}
