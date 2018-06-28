<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Stroke Style.
 */
class StrokeStyle extends Base
{
    /** @var string */
    public $color;

    /** @var string|integer */
    public $width;

    /** @var string */
    public $style;

    /**
     * @var string[] Valid styles
     */
    private $validStyles =
        [
            'solid',
            'dashed',
            'dotted'
        ];

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'color' => 'string',
            'width' => 'string|integer',
            'style' => 'string'
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->style !== null) {
            Assertion::choice($this->style, $this->validStyles, 'style does not have a valid value.');
        }

        parent::validateProperties();
    }
}

