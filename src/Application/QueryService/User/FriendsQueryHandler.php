<?php

namespace Application\QueryService\User;

use Document\Element\Collection;
use Document\Query\Query;
use Document\Query\QueryHandler;
use Document\Serializer;

class FriendsQueryHandler implements QueryHandler
{
    /**
     * @var FriendsQueryRepository
     */
    private $repository;

    private $serializer;

    public function __construct(FriendsQueryRepository $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param Query $query
     *
     * @return Collection
     */
    public function handle(Query $query)
    {
        $data = $this->repository->findFriends($query->getUserId(), $query->getPageNumber(), $query->getPageSize());

        return new Collection($data, $this->serializer);
    }

    public function support()
    {
        return 'Application\QueryService\User\FriendsQuery';
    }

}