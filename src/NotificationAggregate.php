<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\Aggregate;
use Gdbots\Ncr\AggregateResolver;
use Gdbots\Pbj\Message;
use Gdbots\Pbj\WellKnown\NodeRef;
use Gdbots\Schemas\Ncr\Enum\NodeStatus;
use Triniti\Notify\Exception\InvalidNotificationContent;
use Triniti\Schemas\Notify\Enum\NotificationSendStatus;

class NotificationAggregate extends Aggregate
{
    protected function enrichNodeCreated(Message $event): void
    {
        parent::enrichNodeCreated($event);

        /** @var Message $node */
        $node = $event->get('node');
        $node
            ->clear('sent_at')
            ->set('status', NodeStatus::PUBLISHED());

        if (
            !$node->has('send_at')
            && $node->has('content_ref')
            && $node->get('send_on_publish')
        ) {
            /** @var NodeRef $contentRef */
            $contentRef = $node->get('content_ref');
            $aggregate = AggregateResolver::resolve($contentRef->getQName())::fromNodeRef($contentRef, $this->pbjx);
            $aggregate->sync();
            $content = $aggregate->getNode();

            if (
                !$content::schema()->hasMixin('triniti:notify:mixin:has-notifications')
                || !$content::schema()->hasMixin('gdbots:ncr:mixin:publishable')
            ) {
                throw new InvalidNotificationContent();
            }

            $node->set('title', $content->get('title'));
            if ($content->has('published_at')) {
                $sendAt = clone $content->get('published_at');
                $node->set('send_at', $sendAt->modify('+10 seconds'));
            }
        }

        if ($node->has('send_at')) {
            $node->set('send_status', NotificationSendStatus::SCHEDULED());
        } else {
            $node->set('send_status', NotificationSendStatus::DRAFT());
        }
    }

    /**
     * This is for legacy uses of command/event mixins for common
     * ncr operations. It will be removed in 3.x.
     *
     * @param string $name
     * @param array  $arguments
     *
     * @return mixed
     */
    public function __call(string $name, array $arguments)
    {
        $newName = str_replace('Notification', 'Node', $name);
        if ($newName !== $name && is_callable([$this, $newName])) {
            return $this->$newName(...$arguments);
        }
    }
}
