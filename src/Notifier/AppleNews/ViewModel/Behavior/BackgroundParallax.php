<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior;

/**
 * An Apple News Background Parallax Behavior
 */
class BackgroundParallax extends Behavior
{
    /** @var string */
    public $type = 'background_parallax';

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
