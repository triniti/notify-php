<?php
declare(strict_types=1);

namespace Triniti\Notify\Exception;

use Gdbots\Pbj\Exception\HasEndUserMessage;
use Gdbots\Schemas\Pbjx\Enum\Code;

final class NotificationAlreadyScheduled extends \RuntimeException implements TrinitiNotifyException, HasEndUserMessage
{
    /**
     * @param string $message
     */
    public function __construct(string $message = 'Notification already scheduled.')
    {
        parent::__construct($message, Code::ALREADY_EXISTS);
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
