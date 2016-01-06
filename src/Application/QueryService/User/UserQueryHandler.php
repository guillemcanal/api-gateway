<?php

namespace Application\QueryService\User;

use Document\Element\Resource;
use Document\Query\Query;
use Document\Query\QueryHandler;
use Document\Serializer;

class UserQueryHandler implements QueryHandler
{
    /**
     * @var UserQueryRepository
     */
    private $repository;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(UserQueryRepository $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param Query|UserQuery $query
     *
     * @return Resource
     */
    public function handle(Query $query)
    {
        $data = $this->repository->findUser($query->getId());

        return new Resource($data, $this->serializer);
    }

    public function support()
    {
        return 'Application\QueryService\User\UserQuery';
    }

}