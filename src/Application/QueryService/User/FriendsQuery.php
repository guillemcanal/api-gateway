<?php

namespace Application\QueryService\User;

use Document\Query\PageableQuery;
use Document\Query\Query;

class FriendsQuery implements Query, PageableQuery
{
    /**
     * @var int
     */
    private $pageSize;

    /**
     * @var int
     */
    private $pageNumber;

    /**
     * @var int
     */
    private $userId;

    /**
     * @param int $pageSize
     * @param int $pageNumber
     * @param int $userId
     */
    public function __construct($userId, $pageSize = null, $pageNumber = null)
    {
        $this->pageSize = $pageSize;
        $this->pageNumber = $pageNumber;
        $this->userId = $userId;
    }

    /**
     * @return int
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * @return int
     */
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}