<?php

require __DIR__ . '/vendor/autoload.php';

// Api baseUri

$baseUri = 'http://api-mock.dev';

// Http client

$httpClient = new \GuzzleHttp\Client();

// HttpClient Repositories

$userRepository = new \Infrastructure\Persistence\HttpClient\HttpClientUserRepository(
    $httpClient,
    $baseUri
);

$pictureRepository = new \Infrastructure\Persistence\HttpClient\HttpClientPictureRepository(
    $httpClient,
    $baseUri
);

// Query Executor

$queryExecutor = new \Document\Query\QueryExecutor();

// LinkedElements for a User Resource

$userLinkedElements = [
    new \Application\QueryService\User\LinkedElement\UserHasFriends(),
    new \Application\QueryService\User\LinkedElement\UserHasPictures(),
    new \Application\QueryService\User\LinkedElement\UserHasOneLargePicture(),
    new \Application\QueryService\User\LinkedElement\UserHasOneMediumPicture(),
    new \Application\QueryService\User\LinkedElement\UserHasOneSmallPicture()
];

// Serializers

$userSerializer = new \Application\QueryService\User\Serializer\UserSerializer($queryExecutor);
foreach ($userLinkedElements as $userLinkedElement) {
    $userSerializer->addLinkedElement($userLinkedElement);
}

$pictureSerializer = new \Application\QueryService\Picture\Serializer\PictureSerializer($queryExecutor);

// Query handlers

$queryHandlers = [
    new \Application\QueryService\Picture\PictureQueryHandler(
        $pictureRepository,
        $pictureSerializer
    ),
    new \Application\QueryService\Picture\PicturesQueryHandler(
        $pictureRepository,
        $pictureSerializer
    ),
    new \Application\QueryService\User\UserQueryHandler(
        $userRepository,
        $userSerializer
    ),
    new \Application\QueryService\User\FriendsQueryHandler(
        $userRepository,
        $userSerializer
    )
];

// Add handlers into the Query Executor

foreach ($queryHandlers as $queryHandler) {
    $queryExecutor->addHandler($queryHandler);
}

// Resolve a Query

$user = $queryExecutor->execute(new \Application\QueryService\User\UserQuery(1));

// Link resources (user's friends and a small picture for each friends)

$user->with(['friends', 'friends.picture_small']);

// Sparse Fieldsets (filter fields for each resources)

$user->fields([
    'users' => ['name', 'surname', 'email'],
    'pictures' => ['format', 'url']
]);

// Prepare a JsonApi (v1.0) Document

$document = new \Document\Document($user);
$document->addMeta('meta_key', 'meta_value');

// Encode the document to JSON

print json_encode($document, JSON_PRETTY_PRINT);