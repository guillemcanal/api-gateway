<?php

namespace Document\Query;

/**
 * A Query that can be paginated
 */
interface PageableQuery
{
    /**
     * @return int The size of the collection
     */
    public function getPageSize();

    /**
     * @return int THe page number queried by the user
     */
    public function getPageNumber();
}