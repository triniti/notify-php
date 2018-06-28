<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Layout.html#//apple_ref/doc/uid/TP40015408-CH65-SW1
 */
class LayoutObject extends Base
{
    /** @var integer */
    public $columns;

    /** @var integer */
    public $width;

    /** @var integer */
    public $margin;

    /** @var integer */
    public $gutter;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'columns',
            'width'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'columns' => 'integer',
            'width' => 'integer',
            'margin' => 'integer',
            'gutter' => 'integer'
        ));
    }
}
