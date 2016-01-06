<?php

namespace Application\QueryService\User\LinkedElement;

use Document\LinkedElement;
use Application\QueryService\Picture\PictureQuery;

class UserHasOneSmallPicture implements LinkedElement
{
    public function getQuery($model)
    {
        return new PictureQuery($model['id'], 'small');
    }


    public function getType()
    {
        return 'picture_small';
    }
}