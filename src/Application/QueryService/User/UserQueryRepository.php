<?php

namespace Application\QueryService\User;

interface UserQueryRepository
{
    public function findUser($id);
}