<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Pbj\SchemaCurie;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Triniti\Notify\Notifier\NullNotifier;

class ContainerAwareNotifierLocator implements NotifierLocator
{
    /** @var ContainerInterface */
    protected $container;

    /**
     * An array of notifiers keyed by the service id.
     *
     * @var Notifier[]
     */
    protected $notifiers = [];

    /** @var Notifier */
    protected $nullNotifier;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->nullNotifier = new NullNotifier();
    }

    /**
     * @param SchemaCurie $curie
     *
     * @return Notifier
     */
    public function getNotifier(SchemaCurie $curie): Notifier
    {
        $id = $this->curieToServiceId($curie);

        if (isset($this->notifiers[$id])) {
            return $this->notifiers[$id];
        }

        try {
            $this->notifiers[$id] = $this->container->get($id);
        } catch (ServiceNotFoundException $e) {
            $this->notifiers[$id] = $this->nullNotifier;
        } catch (\Throwable $e) {
            throw $e;
        }

        return $this->notifiers[$id];
    }

    /**
     * @param SchemaCurie $curie
     *
     * @return string
     */
    protected function curieToServiceId(SchemaCurie $curie): string
    {
        $message = str_replace('-notification', '', $curie->getMessage());
        return str_replace('-', '_', sprintf('%s_%s.%s_notifier',
            $curie->getVendor(), $curie->getPackage(), $message
        ));
    }
}
