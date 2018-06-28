<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News ImageFill.
 */
class ImageFill extends Base
{
    public $type = 'image';

    /** @var  string */
    public $URL;

    /** @var  string */
    public $attachment;

    /** @var  string */
    public $fillMode;

    /** @var  string */
    public $horizontalAlignment;

    /** @var  string */
    public $verticalAlignment;

    /**
     * @var string[] Valid attachment values
     */
    private $validAttachment =
        [
            'scroll',
            'fixed'
        ];

    /**
     * @var string[] Valid fill mode
     */
    private $validFillMode =
        [
            'fit',
            'cover'
        ];

    /**
     * @var string[] Valid horizontal alignment
     */
    private $validHorizontalAlignment =
        [
            'left',
            'center',
            'right'
        ];

    /**
     * @var string[] Valid vertical alignment
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
            'URL' => 'string',
            'attachment' => 'string',
            'fillMode' => 'string',
            'horizontalAlignment' => 'string',
            'verticalAlignment' => 'string'
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->attachment !== null) {
            Assertion::choice($this->attachment, $this->validAttachment, 'attachment does not have a valid value.');
        }

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
