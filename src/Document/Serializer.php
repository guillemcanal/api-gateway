<?php

namespace Document;

use Document\Query\QueryExecutor;
use Tobscure\JsonApi\AbstractSerializer;
use Tobscure\JsonApi\Relationship;

class Serializer extends AbstractSerializer
{
    /**
     * @var \Document\LinkedElement[]
     */
    protected $linkedElements = [];

    /**
     * @var QueryExecutor
     */
    protected $queryExecutor;

    /**
     * @var array
     */
    protected $resolvedRelationship = [];

    public function __construct(QueryExecutor $queryExecutor)
    {
        $this->queryExecutor = $queryExecutor;
    }

    /**
     * Add
     * @param LinkedElement $relationship
     */
    public function addLinkedElement(LinkedElement $relationship)
    {
        $this->linkedElements[$relationship->getType()] = $relationship;
    }

    public function getRelationship($model, $name)
    {
        // Prevent multiple instantiation
        // @fixme only work with array
        $identifier = md5(serialize($model)).$name;
        if (isset($this->resolvedRelationship[$identifier])) {
            return $this->resolvedRelationship[$identifier];
        }

        if (!isset($this->linkedElements[$name])) {
            throw new \InvalidArgumentException(sprintf('LinkedElement named %s cannot be found', $name));
        }

        $query = $this->linkedElements[$name]->getQuery($model);
        $element = $this->queryExecutor->execute($query);

        $this->resolvedRelationship[$identifier] = new Relationship($element);

        return $this->resolvedRelationship[$identifier];
    }


}