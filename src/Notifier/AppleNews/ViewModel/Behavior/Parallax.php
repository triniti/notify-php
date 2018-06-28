<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Behavior;

/**
 * An Apple News Parallax Behavior
 */
class Parallax extends Behavior
{
    /** @var string */
    public $type = 'parallax';

    /** @var float */
    public $factor;

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
            'type' => 'string',
            'factor' => 'float'
        ));
    }
}

