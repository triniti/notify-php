<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Gdbots\Ncr\Repository\InMemoryNcr;
use Gdbots\Pbjx\EventStore\InMemoryEventStore;
use Gdbots\Pbjx\Pbjx;
use Gdbots\Pbjx\RegisteringServiceLocator;
use Gdbots\Pbjx\Scheduler\Scheduler;
use Gdbots\Schemas\Pbjx\Mixin\Command\Command;
use PHPUnit\Framework\TestCase;

abstract class AbstractPbjxTest extends TestCase
{
    /** @var RegisteringServiceLocator */
    protected $locator;

    /** @var Pbjx */
    protected $pbjx;

    /** @var InMemoryEventStore */
    protected $eventStore;

    /** @var InMemoryNcr */
    protected $ncr;

    /** @var Scheduler */
    protected $scheduler;

    protected function setup()
    {
        $this->locator = new RegisteringServiceLocator();
        $this->pbjx = $this->locator->getPbjx();
        $this->eventStore = new InMemoryEventStore($this->pbjx);
        $this->locator->setEventStore($this->eventStore);
        $this->ncr = new InMemoryNcr();

        $this->scheduler = new class implements Scheduler
        {
            public $lastSendAt;
            public $lastCancelJobs;

            public function createStorage(): void
            {
            }

            public function describeStorage(): string
            {
                return '';
            }

            public function sendAt(Command $command, int $timestamp, ?string $jobId = null): string
            {
                $this->lastSendAt = [
                    'command'   => $command,
                    'timestamp' => $timestamp,
                    'job_id'    => $jobId,
                ];
                return $jobId ?: 'jobid';
            }

            public function cancelJobs(array $jobIds): void
            {
                $this->lastCancelJobs = $jobIds;
            }
        };

        $this->locator->setScheduler($this->scheduler);
    }
}
