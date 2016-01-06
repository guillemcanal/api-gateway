<?php

namespace Document\Element;

use GuzzleHttp\Promise\PromiseInterface;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\SerializerInterface;
use GuzzleHttp\Promise;
use Tobscure\JsonApi\Collection as BaseCollection;

class Collection extends BaseCollection implements ElementPromise
{
    /**
     * @var array
     */
    private $relationships;

    /**
     * @var array
     */
    private $fields;

    /**
     * @var PromiseInterface
     */
    private $promise;

    protected $serializer;

    public function __construct($element, SerializerInterface $serializer)
    {
        if (!($element instanceof PromiseInterface)) {
            $element = new FulfilledPromise($element);
        }

        $element->then(function($data) use ($serializer) {
            return $this->onFulfilledPromise($data, $serializer);
        });

        $this->promise = $element;
    }

    /**
     * Return Pending Promises from an array of elements
     *
     * @param ElementInterface[] $elements
     *
     * @return Promise[]
     */
    protected function getPendingPromisesFromElements(array $elements)
    {
        $promises = [];
        foreach ($elements as $element) {
            if ($element instanceof ElementPromise) {
                $promise = $element->getPromise();
                if (null !== $promise && PromiseInterface::PENDING === $promise->getState()) {
                    $promises[] = $promise;
                }
            }
        }

        return $promises;
    }

    protected function onFulfilledPromise($data, SerializerInterface $serializer)
    {
        $this->resources = $this->buildResources($data, $serializer);

        return $data;
    }

    public function getPromise()
    {
        return $this->promise;
    }

    public function getResources()
    {
        return $this->resources;
    }

    public function with($relationships)
    {
        $this->relationships = $relationships;

        return $this;
    }

    public function fields($fields)
    {
        $this->fields = $fields;

        return $this;
    }

    protected function buildResources($data, SerializerInterface $serializer)
    {
        $resources = [];
        $relationships = [];

        foreach ($data as $resource) {

            if (! ($resource instanceof Resource)) {
                $resource = new Resource($resource, $serializer);
            }

            $resource->with($this->relationships);
            $resource->fields($this->fields);

            $relationships = array_merge($relationships, $resource->getRelationShips());

            $resources[] = $resource;
        }

        $elements = array_map(function(Relationship $relationship) {
            return $relationship->getData();
        }, $relationships);

        // fulfill promises on resources included elements
        $promises = $this->getPendingPromisesFromElements($elements);
        if (!empty($promises)) {
            Promise\all($promises)->wait();
        }

        return $resources;
    }
}