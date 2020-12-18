<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify\Notifier;

use Gdbots\Pbj\Message;
use Triniti\Notify\Notifier;
use Triniti\Schemas\Notify\NotifierResultV1;

class MockNotifier implements Notifier
{
    public function send(Message $notification, Message $app, ?Message $content = null): Message
    {
        return NotifierResultV1::create();
    }
}
