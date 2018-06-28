<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Tweet.html#//apple_ref/doc/uid/TP40015408-CH37-SW1
 *
 **/
class TweetComponent extends BaseComponent
{
    /** @var string */
    public $role = 'tweet';

    /** @var string */
    public $URL;

    /** @var AnchorObject */
    public $anchor;

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
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'identifier' => 'string',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle',
        ));
    }
}
