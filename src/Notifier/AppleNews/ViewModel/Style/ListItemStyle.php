<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel\Style;

use Assert\Assertion;
use Triniti\Notify\Notifier\AppleNews\ViewModel\Base;

/**
 * An Apple News Document ListItemStyle
 */
class ListItemStyle extends Base
{
    /** @var string */
    public $type;

    /** @var string */
    public $character;

    /**
     * @var string[] Valid types
     */
    private $validTypes =
        [
            'bullet',
            'decimal',
            'lower_alphabetical',
            'upper_alphabetical',
            'lower_roman',
            'upper_roman',
            'character',
            'none'
        ];

    /**
     * Define required properties.
     */
    protected function required()
    {
        return array_merge(parent::required(), array(
            'type'
        ));
    }

    /**
     * Define property constraints.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'type' => 'string',
            'character' => 'string',
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->type !== null) {
            Assertion::choice($this->type, $this->validTypes, 'type does not have a valid value.');
        }

        parent::validateProperties();
    }
}
