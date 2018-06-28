<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Component;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Photo.html#//apple_ref/doc/uid/TP40015408-CH28-SW1
 *
 **/
class PhotoComponent extends ImageComponent
{
    /** @var string */
    public $role = 'photo';

    public function __construct($url)
    {
        parent::__construct($url);
    }

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'role',
            'URL'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'role' => 'string',
            'URL' => 'string',
        ));
    }
}
