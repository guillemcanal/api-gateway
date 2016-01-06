<?php

namespace Application\QueryService\User\LinkedElement;

use Document\LinkedElement;
use Application\QueryService\Picture\PictureQuery;

class UserHasOneLargePicture implements LinkedElement
{
    public function getQuery($model)
    {
        return new PictureQuery($model['id'], 'large');
    }

    public function getType()
    {
        return 'picture_large';
    }
}