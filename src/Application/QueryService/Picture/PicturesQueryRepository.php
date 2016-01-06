<?php

namespace Application\QueryService\Picture;

interface PicturesQueryRepository
{
    public function findUserPictures($userId);
}