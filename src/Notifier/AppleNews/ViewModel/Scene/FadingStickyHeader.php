<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Scene;

class FadingStickyHeader extends Scene
{
    /** @var string */
    public $type = 'fading_sticky_header';

    /** @var string */
    public $fadeColor;

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
            'fadeColor' => 'string'
        ));
    }
}
