<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Animation;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Component Animation
 */
class ComponentAnimation extends Base
{
    /** @var string */
    public $type;

    /** @var boolean */
    public $userControllable;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string',
            'userControllable' => 'boolean'
        ));
    }
}
