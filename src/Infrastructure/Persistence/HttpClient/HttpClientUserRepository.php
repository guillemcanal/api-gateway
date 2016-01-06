<?php

namespace Infrastructure\Persistence\HttpClient;

use Application\QueryService\User\FriendsQueryRepository;
use Application\QueryService\User\UserQueryRepository;
use GuzzleHttp\Psr7\Response;

class HttpClientUserRepository extends HttpClientRepository implements UserQueryRepository, FriendsQueryRepository
{
    public function findFriends($userId, $pageNumber, $pageSize)
    {
        return $this->getAsync('/friends/{userId}{?page,page_size}', [
                'parameters' => [
                    'userId' => $userId,
                    'page' => $pageNumber,
                    'page_size' => $pageSize
                ]
            ])->then(function(Response $response) {
                return json_decode($response->getBody(), true)['data'];
            });
    }

    public function findUser($id)
    {
        return $this->getAsync('/users/{id}', [
            'parameters' => [
                'id' => $id,
            ]
        ])->then(function(Response $response) {
            return json_decode($response->getBody(), true)['data'];
        });
    }
}