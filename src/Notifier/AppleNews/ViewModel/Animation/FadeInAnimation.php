<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Animation;

/**
 * An Apple News Fade In Animation
 */
class FadeInAnimation extends ComponentAnimation
{
    /** @var string */
    public $type = 'fade_in';

    /** @var float */
    public $initialAlpha;

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
            'initialAlpha' => 'float',
            'userControllable' => 'boolean'
        ));
    }
}
