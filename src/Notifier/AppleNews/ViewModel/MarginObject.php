<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

/**
 * Class MarginObject
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Margin.html#//apple_ref/doc/uid/TP40015408-CH66-SW1
 * @package Triniti\Notify\Notifier\AppleNews\ViewModel
 *
 */

class MarginObject extends Base
{
    /** @var string|integer */
    public $top;
    /** @var string|integer */
    public $bottom;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'top' => 'string|integer',
            'bottom' => 'string|integer'
        ));
    }
}
