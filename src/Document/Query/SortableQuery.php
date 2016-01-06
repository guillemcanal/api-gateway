<?php

namespace Document\Query;

/**
 * A Query that can be sorted
 */
interface SortableQuery
{
    const ORDER_ASC = 'ASC';
    const ORDER_DESC = 'DESC';

    /**
     * @return string sort direction (ORDER_ASC or ORDER_DESC)
     */
    public function getSortOrder();

    /**
     * @return string The field used to perform the sort
     */
    public function getSortField();
}