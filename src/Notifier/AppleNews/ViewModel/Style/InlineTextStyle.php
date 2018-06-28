<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Inline Text Style.
 */
class InlineTextStyle extends Base
{
    /** @var integer */
    public $rangeLength;

    /** @var integer */
    public $rangeStart;

    /** @var string|TextStyle */
    public $textStyle;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'rangeLength',
            'rangeStart',
            'textStyle'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'rangeLength' => 'integer',
            'rangeStart' => 'integer',
            'textStyle' => 'string|Triniti\Notify\Notifier\AppleNews\ViewModel\Style\TextStyle'
        ));
    }
}
