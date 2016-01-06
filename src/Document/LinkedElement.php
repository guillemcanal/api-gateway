<?php

namespace Document;

/**
 * Link an element (either a Collection or a Resource) to another
 */
interface LinkedElement
{
    /**
     * @param mixed $model data provided by one parent element
     *
     * @return Query
     */
    public function getQuery($model);

    /**
     * The name of the linked element
     *
     * @return string
     */
    public function getType();
}