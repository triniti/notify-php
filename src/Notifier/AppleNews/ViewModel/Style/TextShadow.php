<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document TextShadow object
 */
class TextShadow extends Base
{
    /** @var string */
    public $color;

    /** @var float */
    public $radius;

    /** @var float */
    public $opacity;

    /** @var Offset */
    public $offset;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'color',
            'radius'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'color' => 'string',
            'radius' => 'float',
            'opacity' => 'float',
            'offset' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Offset'
        ));
    }
}
