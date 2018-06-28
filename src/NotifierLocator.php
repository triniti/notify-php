<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Pbj\SchemaCurie;

interface NotifierLocator
{
    /**
     * @param SchemaCurie $curie
     *
     * @return Notifier
     */
    public function getNotifier(SchemaCurie $curie): Notifier;
}
