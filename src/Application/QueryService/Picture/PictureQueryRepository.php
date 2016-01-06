<?php

namespace Application\QueryService\Picture;

interface PictureQueryRepository
{
    public function findOneUserPicture($userId, $format);
}