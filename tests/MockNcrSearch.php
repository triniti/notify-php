<?php
declare(strict_types=1);

namespace Triniti\Tests\Notify;

use Gdbots\Ncr\NcrSearch;
use Gdbots\Pbj\SchemaQName;
use Gdbots\QueryParser\ParsedQuery;
use Gdbots\Schemas\Ncr\Mixin\SearchNodesRequest\SearchNodesRequest;
use Gdbots\Schemas\Ncr\Mixin\SearchNodesResponse\SearchNodesResponse;

class MockNcrSearch implements NcrSearch
{
    public function createStorage(SchemaQName $qname, array $context = []): void
    {
        // do nothing
    }

    public function describeStorage(SchemaQName $qname, array $context = []): string
    {
        // do nothing
    }

    public function indexNodes(array $nodes, array $context = []): void
    {
        // do nothing
    }

    public function deleteNodes(array $nodeRefs, array $context = []): void
    {
        // do nothing
    }

    public function searchNodes(SearchNodesRequest $request,
                                ParsedQuery $parsedQuery,
                                SearchNodesResponse $response,
                                array $qnames = [],
                                array $context = []
    ): void {
        // do nothing
    }
}
