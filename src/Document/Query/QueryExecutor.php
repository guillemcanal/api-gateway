<?php

namespace Document\Query;

use Tobscure\JsonApi\ElementInterface;

/**
 * Resolve a QueryHandler from a Query object
 */
class QueryExecutor
{
    /**
     * @var QueryHandler[]
     */
    private $handlers = [];

    /**
     * A QueryHandler must return a Collection or a Resource
     *
     * @param Query $query
     *
     * @return ElementInterface
     */
    public function execute(Query $query)
    {
        return $this->findHandler($query)->handle($query);
    }

    public function addHandler(QueryHandler $handler)
    {
        $this->handlers[$handler->support()] = $handler;
    }

    private function findHandler(Query $query)
    {
        $queryClassName = get_class($query);
        if (!isset($this->handlers[$queryClassName])) {
            throw new \RuntimeException('No QueryHandler found for ' . $queryClassName);
        }

        return $this->handlers[$queryClassName];
    }
}