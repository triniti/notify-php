<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Addition;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * Class LinkAddition
 * @package Triniti\Notify\Notifier\AppleNews\ViewModel\Addition
 *
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/LinkAddition.html#//apple_ref/doc/uid/TP40015408-CH88-SW1
 *
 */
class LinkAddition extends Base
{
    public $type = 'link';

    /** @var integer */
    public $rangeLength;

    /** @var integer */
    public $rangeStart;

    /** @var string */
    public $URL;

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type',
            'rangeLength',
            'rangeStart',
            'URL'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string',
            'rangeLength' => 'integer',
            'rangeStart' => 'integer',
            'URL' => 'string'
        ));
    }
}
