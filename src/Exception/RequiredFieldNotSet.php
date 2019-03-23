<?php
declare(strict_types=1);

namespace Triniti\Notify\Exception;

use Gdbots\Pbj\Exception\HasEndUserMessage;
use Gdbots\Schemas\Pbjx\Enum\Code;

final class RequiredFieldNotSet extends \InvalidArgumentException implements TrinitiNotifyException, HasEndUserMessage
{
    /**
     * @param string $message
     */
    public function __construct(string $message = 'Required field is missing')
    {
        parent::__construct($message, Code::INVALID_ARGUMENT);
    }

    /**
     * {@inheritdoc}
     */
    public function getEndUserMessage()
    {
        return $this->getMessage();
    }

    /**
     * {@inheritdoc}
     */
    public function getEndUserHelpLink()
    {
        return null;
    }
}
