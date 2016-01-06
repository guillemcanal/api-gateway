<?php

namespace Application\QueryService\User\LinkedElement;

use Document\LinkedElement;
use Application\QueryService\Picture\PictureQuery;

class UserHasOneMediumPicture implements LinkedElement
{
    public function getQuery($model)
    {
        return new PictureQuery($model['id'], 'medium');
    }

    public function getType()
    {
        return 'picture_medium';
    }
}