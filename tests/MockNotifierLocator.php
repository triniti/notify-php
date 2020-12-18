<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Gdbots\Pbj\SchemaCurie;
use Triniti\Notify\Notifier;
use Triniti\Notify\NotifierLocator;
use Triniti\Tests\Notify\Notifier\MockNotifier;

class MockNotifierLocator implements NotifierLocator
{
    public function getNotifier(SchemaCurie $curie): Notifier
    {
        return new MockNotifier();
    }
}
