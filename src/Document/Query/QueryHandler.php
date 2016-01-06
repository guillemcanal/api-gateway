<?php

namespace Document\Query;

use Tobscure\JsonApi\ElementInterface;

interface QueryHandler
{
    /**
     * Handle a Query object
     *
     * @param Query $query
     *
     * @return ElementInterface
     */
    public function handle(Query $query);

    /**
     * Return the supported Query FQCN
     *
     * @return string
     */
    public function support();
}