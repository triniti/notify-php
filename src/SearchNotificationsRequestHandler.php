<?php
declare(strict_types=1);

namespace Triniti\Notify;

use Gdbots\Ncr\AbstractSearchNodesRequestHandler;
use Gdbots\Pbj\Schema;
use Gdbots\QueryParser\Enum\BoolOperator;
use Gdbots\QueryParser\Node\Field;
use Gdbots\QueryParser\Node\Word;
use Gdbots\QueryParser\ParsedQuery;
use Gdbots\Schemas\Ncr\Mixin\SearchNodesRequest\SearchNodesRequest;
use Triniti\Schemas\Notify\Mixin\Notification\NotificationV1Mixin;
use Triniti\Schemas\Notify\Mixin\SearchNotificationsRequest\SearchNotificationsRequestV1Mixin;

class SearchNotificationsRequestHandler extends AbstractSearchNodesRequestHandler
{
    /**
     * {@inheritdoc}
     */
    protected function createQNamesForSearchNodes(SearchNodesRequest $request, ParsedQuery $parsedQuery): array
    {
        $validQNames = [];

        /** @var Schema $schema */
        foreach (NotificationV1Mixin::findAll() as $schema) {
            $qname = $schema->getQName();
            $validQNames[$qname->getMessage()] = $qname;
        }

        $qnames = [];
        foreach ($request->get('types', []) as $type) {
            if (isset($validQNames[$type])) {
                $qnames[] = $validQNames[$type];
            }
        }

        if (empty($qnames)) {
            $qnames = array_values($validQNames);
        }

        return $qnames;
    }

    /**
     * {@inheritdoc}
     */
    protected function beforeSearchNodes(SearchNodesRequest $request, ParsedQuery $parsedQuery): void
    {
        parent::beforeSearchNodes($request, $parsedQuery);
        $required = BoolOperator::REQUIRED();

        if ($request->has('app_ref')) {
            $parsedQuery->addNode(
                new Field(
                    'app_ref',
                    new Word((string)$request->get('app_ref'), $required),
                    $required
                )
            );
        }

        if ($request->has('content_ref')) {
            $parsedQuery->addNode(
                new Field(
                    'content_ref',
                    new Word((string)$request->get('content_ref'), $required),
                    $required
                )
            );
        }

        if ($request->has('send_status')) {
            $parsedQuery->addNode(
                new Field(
                    'send_status',
                    new Word((string)$request->get('send_status'), $required),
                    $required
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function handlesCuries(): array
    {
        return [
            SearchNotificationsRequestV1Mixin::findOne()->getCurie(),
        ];
    }
}
