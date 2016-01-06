<?php

namespace Application\QueryService\Picture;

use Document\Query\Query;

class PictureQuery implements Query
{
    /**
     * @var int
     */
    private $userId;

    /**
     * @var string
     */
    private $format;

    /**
     * PictureQuery constructor.
     * @param int $userId
     * @param string $format
     */
    public function __construct($userId, $format)
    {
        $this->userId = $userId;
        $this->format = $format;
    }

    /**
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

}