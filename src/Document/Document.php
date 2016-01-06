<?php

namespace Document;

use Document\Element\ElementPromise;
use Tobscure\JsonApi\Document as BaseDocument;

class Document extends BaseDocument
{
    public function toArray()
    {
        // We resolve the root element
        if ($this->data instanceof ElementPromise && null !== $promise = $this->data->getPromise()) {
            $promise->wait();
        }

        return parent::toArray();
    }

}