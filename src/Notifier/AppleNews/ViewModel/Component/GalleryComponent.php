<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior;
use Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Gallery.html#//apple_ref/doc/uid/TP40015408-CH17-SW1
 *
 **/
class GalleryComponent extends BaseComponent
{
    /** @var string */
    public $role = 'gallery';

    /** @var GalleryItemObject[] */
    public $items = [];

    /** @var AnchorObject */
    public $anchor;

    /** @var ComponentAnimation */
    public $animation;

    /** @var Behavior */
    public $behavior;

    /** @var string */
    public $identifier;

    /** @var ComponentLayoutObject|string */
    public $layout;

    /** @var ComponentStyle|string */
    public $style;

    /**
     * @param GalleryItemObject $image
     * @throws \Exception
     */
    public function addImage(GalleryItemObject $image)
    {
        if (!isset($image->URL)) {
            //throw new Exception('Image url must set');
        }
        $this->items[] = $image;
    }

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role',
            'items'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'items' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\GalleryItemObject[]',
            'anchor' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\AnchorObject',
            'animation' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Animation\ComponentAnimation',
            'behavior' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior\Behavior',
            'identifier' => 'string',
            'layout' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\ComponentLayoutObject',
            'style' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ComponentStyle'
        ));
    }
}
