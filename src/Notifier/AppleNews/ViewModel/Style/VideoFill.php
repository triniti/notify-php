<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News VideoFill.
 */
class VideoFill extends Base
{
    /** @var  string */
    public $type = 'video';

    public $URL;

    /** @var  string */
    public $stillURL;

    // optional
    /** @var  string */
    public $fillMode;

    /** @var  boolean */
    public $loop;

    /** @var  string */
    public $verticalAlignment;

    /** @var  string */
    public $horizontalAlignment;

    /**
     * @var string[] Valid fill mode values
     */
    private $validFillMode =
        [
            'fit',
            'cover'
        ];
    /**
     * @var string[] Valid horizontal alignment values
     */
    private $validHorizontalAlignment =
        [
            'left',
            'center',
            'right'
        ];
    /**
     * @var string[] Valid vertical alignment values
     */
    private $validVerticalAlignment =
        [
            'top',
            'center',
            'bottom'
        ];

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type',
            'URL',
            'stillURL',
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string',
            'URL' => 'string',
            'stillURL' => 'string',
            'fillMode' => 'string',
            'loop' => 'boolean',
            'horizontalAlignment' => 'string',
            'verticalAlignment' => 'string'
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->fillMode !== null) {
            Assertion::choice($this->fillMode, $this->validFillMode, 'fillMode does not have a valid value.');
        }

        if ($this->horizontalAlignment !== null) {
            Assertion::choice($this->horizontalAlignment, $this->validHorizontalAlignment, 'horizontalAlignment does not have a valid value.');
        }

        if ($this->verticalAlignment !== null) {
            Assertion::choice($this->verticalAlignment, $this->validVerticalAlignment, 'verticalAlignment does not have a valid value.');
        }

        parent::validateProperties();
    }
}
