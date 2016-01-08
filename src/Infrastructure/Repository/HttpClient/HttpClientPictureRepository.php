<?php

namespace Infrastructure\Repository\HttpClient;

use Application\QueryService\Picture\PictureQueryRepository;
use Application\QueryService\Picture\PicturesQueryRepository;
use GuzzleHttp\Psr7\Response;

class HttpClientPictureRepository extends HttpClientRepository implements PicturesQueryRepository, PictureQueryRepository
{
    public function findOneUserPicture($userId, $format)
    {
        return $this->getAsync('/pictures/{userId}/{format}', [
            'parameters' => [
                'userId' => $userId,
                'format' => $format
            ]
        ])->then(function(Response $response) {
            return json_decode($response->getBody(), true)['data'];
        });

    }

    public function findUserPictures($userId)
    {
        return $this->getAsync('/pictures/{userId}', [
            'parameters' => [
                'userId' => $userId
            ]
        ])->then(function(Response $response) {
            return json_decode($response->getBody(), true)['data'];
        });

        return $promise;
    }

}