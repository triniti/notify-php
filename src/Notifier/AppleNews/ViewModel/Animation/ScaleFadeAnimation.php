<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Animation;

/**
 * An Apple News Scale Fade Animation
 */
class ScaleFadeAnimation extends ComponentAnimation
{
    /** @var string */
    public $type = 'scale_fade';

    /** @var float */
    public $initialAlpha;

    /** @var float */
    public $initialScale;

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
            'initialScale' => 'float',
            'userControllable' => 'boolean'
        ));
    }
}
