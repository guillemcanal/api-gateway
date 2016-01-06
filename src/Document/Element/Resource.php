<?php

namespace Document\Element;

use GuzzleHttp\Promise\PromiseInterface;
use Tobscure\JsonApi\Relationship;
use Tobscure\JsonApi\Resource as BaseResource;
use GuzzleHttp\Promise;
use Tobscure\JsonApi\SerializerInterface;

class Resource extends BaseResource implements ElementPromise
{
    protected $relationships;

    protected $promise;

    protected $serializer;

    public function __construct($element, SerializerInterface $serializer)
    {
        $this->serializer = $serializer;

        if ($element instanceof PromiseInterface) {
            $this->promise = $element;
            $element->then(function($data) {
                $this->onFulfilledPromise($data);
            });
        } else {
            $this->onFulfilledPromise($element);
        }
    }

    public function getRelationships()
    {
        if (null === $this->relationships) {
            $this->relationships = $this->buildRelationships();
        }

        return $this->relationships;
    }


    protected function onFulfilledPromise($data)
    {
        $this->setData($data);
        $this->fulfillIncludedElements();

        return $data;
    }

    public function getPromise()
    {
        return $this->promise;
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
                if ($element && PromiseInterface::PENDING === $promise->getState()) {
                    $promises[] = $promise;
                }
            }
        }

        return $promises;
    }

    private function fulfillIncludedElements()
    {
        $elements = array_map(function(Relationship $relationship) {
            return $relationship->getData();
        }, $this->buildRelationships());

        // fulfill included elements
        $promises = $this->getPendingPromisesFromElements($elements);
        if (!(empty($promises))) {
            Promise\all($promises)->wait();
        }

    }

}