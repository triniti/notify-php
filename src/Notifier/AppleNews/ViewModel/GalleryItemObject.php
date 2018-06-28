<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/GalleryItem.html#//apple_ref/doc/uid/TP40015408-CH63-SW1
 */
class GalleryItemObject extends Base
{
    /** @var string */
    public $URL;

    /** @var string|CaptionDescriptor */
    public $caption;

    /** @var string */
    public $accessibilityCaption;

    /** @var boolean */
    public $explicitContent;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'URL'
        ));
    }

    /**
     * Define required properties.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'URL' => 'string',
            'caption' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\CaptionDescriptor',
            'accessibilityCaption' => 'string',
            'explicitContent' => 'boolean'
        ));
    }
}
