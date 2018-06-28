<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document Style.
 */
class DocumentStyle extends Base
{
    /** @var string */
    public $backgroundColor;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'backgroundColor'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'backgroundColor' => 'string'
        ));
    }
}

