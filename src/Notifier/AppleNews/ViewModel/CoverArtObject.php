<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/CoverArt.html#//apple_ref/doc/uid/TP40015408-CH108-SW1
 */
class CoverArtObject extends Base
{
    /** @var string */
    public $type = 'image';

    /** @var string */
    public $URL;

    /** @var string */
    public $accessibilityCaption;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type',
            'URL'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string',
            'URL' => 'string',
            'accessibilityCaption' => 'string'
        ));
    }
}
