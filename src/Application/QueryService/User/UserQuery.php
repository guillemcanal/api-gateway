<?php

namespace Application\QueryService\User;

use Document\Query\Query;

class UserQuery implements Query
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

}