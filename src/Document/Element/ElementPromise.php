<?php

namespace Document\Element;

use GuzzleHttp\Promise\PromiseInterface;

interface ElementPromise
{
    /**
     * @return PromiseInterface
     */
    public function getPromise();
}