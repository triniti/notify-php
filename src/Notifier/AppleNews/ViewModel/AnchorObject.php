<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier\AppleNews\ViewModel;

use Assert\Assertion;

/**
 * @link: https://developer.apple.com/library/content/documentation/General/Conceptual/Apple_News_Format_Ref/Anchor.html#//apple_ref/doc/uid/TP40015408-CH51-SW1
 *
 */
class AnchorObject extends Base
{
    /** @var string */
    public $targetAnchorPosition = 'bottom';

    /** @var string */
    public $originAnchorPosition = 'bottom';

    /** @var integer */
    public $rangeLength;

    /** @var integer */
    public $rangeStart;

    /** @var string */
    public $target;

    /** @var string */
    public $targetComponentIdentifier;

    /**
     * @var string[] Valid position values
     */
    private $validPositions =
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
            'targetAnchorPosition'
        ));
    }

    /**
     * Define required properties.
     */
    protected function constraints()
    {
        return array_merge(parent::constraints(), array(
            'targetAnchorPosition' => 'string',
            'originAnchorPosition' => 'string',
            'rangeLength' => 'integer',
            'rangeStart' => 'integer',
            'target' => 'string',
            'targetComponentIdentifier' => 'string'
        ));
    }

    /**
     * Validate properties are correct type and value.
     */
    public function validateProperties()
    {
        if ($this->targetAnchorPosition !== null) {
            Assertion::choice($this->targetAnchorPosition, $this->validPositions, 'targetAnchorPosition does not have a valid value.');
        }

        parent::validateProperties();
    }
}
