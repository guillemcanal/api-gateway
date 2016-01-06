<?php

namespace Application\QueryService\Picture;

use Document\Query\Query;

class PicturesQuery implements Query
{
    private $userId;

    /**
     * PicturesQuery constructor.
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }
}