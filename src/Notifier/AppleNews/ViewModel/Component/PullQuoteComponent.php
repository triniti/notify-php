<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Pullquote.html#//apple_ref/doc/uid/TP40015408-CH31-SW1
 *
 **/
class PullQuoteComponent extends QuoteComponent
{
    /** @var string */
    public $role = 'pullquote';

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role',
            'text'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'text' => 'string'
        ));
    }
}
