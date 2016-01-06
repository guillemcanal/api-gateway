<?php

namespace Application\QueryService\Picture;

use Document\Element\Resource;
use Document\Query\Query;
use Document\Query\QueryHandler;
use Document\Serializer;

class PictureQueryHandler implements QueryHandler
{
    /**
     * @var PictureQueryRepository
     */
    private $repository;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(PictureQueryRepository $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param Query|PictureQuery $query
     *
     * @return Resource
     */
    public function handle(Query $query)
    {
        $data = $this->repository->findOneUserPicture($query->getUserId(), $query->getFormat());

        return new Resource($data, $this->serializer);
    }

    public function support()
    {
        return 'Application\QueryService\Picture\PictureQuery';
    }

}