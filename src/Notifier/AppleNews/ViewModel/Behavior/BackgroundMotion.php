<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior;

/**
 * An Apple News Background Motion Behavior
 */
class BackgroundMotion extends Behavior
{
    /** @var String */
    public $type = 'background_motion';

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
