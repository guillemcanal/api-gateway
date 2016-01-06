<?php

namespace Application\QueryService\User\LinkedElement;

use Document\LinkedElement;
use Application\QueryService\User\FriendsQuery;

class UserHasFriends implements LinkedElement
{
    public function getQuery($model)
    {
        return new FriendsQuery($model['id']);
    }

    public function getType()
    {
        return 'friends';
    }
}