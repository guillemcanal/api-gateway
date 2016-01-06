<?php

namespace Application\QueryService\User\LinkedElement;

use Document\LinkedElement;
use Application\QueryService\Picture\PicturesQuery;

class UserHasPictures implements LinkedElement
{
    public function getQuery($model)
    {
        return new PicturesQuery($model['id']);
    }

    public function getType()
    {
        return 'pictures';
    }

}