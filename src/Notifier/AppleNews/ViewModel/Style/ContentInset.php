<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document ContentInset.
 */
class ContentInset extends Base
{
    /** @var boolean */
    public $bottom;

    /** @var boolean */
    public $left;

    /** @var boolean */
    public $right;

    /** @var boolean */
    public $top;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'bottom' => 'boolean',
            'left' => 'boolean',
            'right' => 'boolean',
            'top' => 'boolean'
        ));
    }
}
