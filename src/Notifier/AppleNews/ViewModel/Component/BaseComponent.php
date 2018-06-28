<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Component.html#//apple_ref/doc/uid/TP40015408-CH5-SW1
 *
 **/
class BaseComponent extends Base
{
    /** @var string */
    public $role;

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string'
        ));
    }
}
