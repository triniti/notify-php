<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Schemas\Iam\Mixin\App\App;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\NotifierResult;

interface Notifier
{
    /**
     * @param Notification     $notification
     * @param App              $app
     * @param HasNotifications $content
     *
     * @return NotifierResult
     */
    public function send(Notification $notification, App $app, ?HasNotifications $content = null): NotifierResult;
}
