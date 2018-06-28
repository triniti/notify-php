<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/MapItem.html#//apple_ref/doc/uid/TP40015408-CH100-SW1
 */
class MapItemObject extends Base
{
    /** @var float */
    public $latitude;

    /** @var float */
    public $longitude;

    /** @var string */
    public $caption;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
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
            'latitude' => 'float',
            'longitude' => 'float',
            'caption' => 'string'
        ));
    }
}
