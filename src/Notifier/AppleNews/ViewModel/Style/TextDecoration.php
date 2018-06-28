<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News TextDecoration object.
 */
class TextDecoration extends Base
{
    /** @var string */
    public $color;

    /** @var integer */
    public $width;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'color' => 'string',
            'width' => 'integer'
        ));
    }
}
