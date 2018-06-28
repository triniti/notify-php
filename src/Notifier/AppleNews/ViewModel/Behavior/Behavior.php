<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Component Behavior
 */
class Behavior extends Base
{
    /** @var string */
    public $type;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type',
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string'
        ));
    }
}
