<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/MapSpan.html#//apple_ref/doc/uid/TP40015408-CH101-SW1
 */
class MapSpanObject extends Base
{
    /** @var float */
    public $latitudeDelta;

    /** @var float */
    public $longitudeDelta;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'latitudeDelta',
            'longitudeDelta'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'latitudeDelta' => 'float',
            'longitudeDelta' => 'float'
        ));
    }
}
