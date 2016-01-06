<?php

namespace Application\QueryService\User;

interface FriendsQueryRepository
{
    public function findFriends($userId, $pageNumber, $pageSize);
}