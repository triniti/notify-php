<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Border
 */
class Border extends Base
{
    /** @var StrokeStyle */
    public $all;

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
            'all' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\StrokeStyle',
            'bottom' => 'boolean',
            'left' => 'boolean',
            'right' => 'boolean',
            'top' => 'boolean'
        ));
    }
}
