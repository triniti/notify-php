<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News DropCapStyle.
 */
class DropCapStyle extends Base
{
    /** @var integer */
    public $numberOfLines;

    /** @var string */
    public $backgroundColor;

    /** @var string */
    public $fontName;

    /** @var integer */
    public $numberOfRaisedLines;

    /** @var integer */
    public $padding;

    /** @var string */
    public $textColor;

    /** @var integer */
    public $numberOfCharacters;

    /**
     * Implements JsonSerializable::jsonSerialize().
     */
    public function jsonSerialize()
    {
        if ($this->numberOfLines > 10 && $this->numberOfLines !== null) {
            $this->numberOfLines = 10;
        } else {
            if ($this->numberOfLines < 2 && $this->numberOfLines !== null) {
                $this->numberOfLines = 2;
            }
        }

        return parent::jsonSerialize();
    }

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'numberOfLines'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'numberOfLines' => 'integer',
            'backgroundColor' => 'string',
            'fontName' => 'string',
            'numberOfRaisedLines' => 'integer',
            'padding' => 'integer',
            'textColor' => 'string',
            'numberOfCharacters' => 'integer'
        ));
    }
}
