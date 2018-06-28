<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document TextStrokeStyle.
 */
class TextStrokeStyle extends Base
{
    /** @var string */
    public $color;

    /** @var integer */
    public $width;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'color'
        ));
    }

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
