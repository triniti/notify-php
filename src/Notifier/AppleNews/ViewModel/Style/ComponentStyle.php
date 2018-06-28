<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document Component Style.
 */
class ComponentStyle extends Base
{
    /** @var string */
    public $backgroundColor;

    /** @var ImageFill|VideoFill */
    public $fill;

    /** @var float */
    public $opacity;

    /** @var Border */
    public $border;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'backgroundColor' => 'string',
            'fill' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\ImageFill|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\VideoFill',
            'opacity' => 'float',
            'border' => 'Triniti\Notify\Notifier\AppleNews\ViewModel\Style\Border',
        ));
    }
}
