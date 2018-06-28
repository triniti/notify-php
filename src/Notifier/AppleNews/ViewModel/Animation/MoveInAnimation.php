<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Animation;

/**
 * An Apple News Move In Animation
 */
class MoveInAnimation extends ComponentAnimation
{
    /** @var string */
    public $type = 'move_in';

    /** @var string */
    public $preferredStartingPosition;

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
            'preferredStartingPosition' => 'string',
            'userControllable' => 'boolean'
        ));
    }
}
