<?php
declare(strict_types=1);

namespace Triniti\Notify\Notifier;

use Gdbots\Schemas\Iam\Mixin\App\App;
use Gdbots\Schemas\Pbjx\Enum\Code;
use Triniti\Notify\Notifier;
use Triniti\Schemas\Notify\Mixin\HasNotifications\HasNotifications;
use Triniti\Schemas\Notify\Mixin\Notification\Notification;
use Triniti\Schemas\Notify\NotifierResult;
use Triniti\Schemas\Notify\NotifierResultV1;

class AndroidNotifier implements Notifier
{
    /**
     * {@inheritdoc}
     */
    public function send(Notification $notification, App $app, ?HasNotifications $content = null): NotifierResult
    {
        return NotifierResultV1::create()
            ->set('ok', false)
            ->set('code', Code::UNIMPLEMENTED)
            ->set('error_name', 'AndroidNotifierNotImplemented');
    }
}
