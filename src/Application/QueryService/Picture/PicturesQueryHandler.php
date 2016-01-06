<?php

namespace Application\QueryService\Picture;

use Document\Element\Collection;
use Document\LegacyCollection;
use Document\Query\Query;
use Document\Query\QueryHandler;
use Document\Serializer;

class PicturesQueryHandler implements QueryHandler
{
    /**
     * @var PicturesQueryRepository
     */
    private $repository;

    /**
     * @var Serializer
     */
    private $serializer;

    public function __construct(PicturesQueryRepository $repository, Serializer $serializer)
    {
        $this->repository = $repository;
        $this->serializer = $serializer;
    }

    /**
     * @param Query $query
     *
     * @return LegacyCollection
     */
    public function handle(Query $query)
    {
        $data = $this->repository->findUserPictures($query->getUserId());

        return new Collection($data, $this->serializer);
    }

    public function support()
    {
        return 'Application\QueryService\Picture\PicturesQuery';
    }

}