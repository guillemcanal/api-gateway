<?php

require __DIR__ . '/vendor/autoload.php';

/**************************************************************
 * BEWARE UGLY CODE INSIDE, it follow the It Work™ principle  *
 **************************************************************/

// To lazy to configure PHP properly, sorry :\
date_default_timezone_set('Europe/Paris');

use Faker\Generator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Generate a JSON response
 *
 * @param Request $request You know that one
 * @param callable $callback A callable that return an object or a collection of objects
 * @param bool $pageable If true, create a pageable resource
 *
 * @return \Symfony\Component\HttpFoundation\Response|static
 */
$generate = function (Request $request, Callable $callback, $pageable = false) {

    $faker = Faker\Factory::create();

    if (true === $pageable) {
        $pageSize = $request->query->get('page_size', 10);
        $pageCurrent = $request->query->get('page', 1);
        $data = [];
        for ($i = 0; $i < $pageSize; $i++) {
            $data[] = $callback($faker);
        }
    } else {
        $data = $callback($faker);
    }

    // create document
    $document = [
        'data' => $data,
    ];

    if (true === $pageable) {
        $pageLast = mt_rand(1, 10);
        $document['meta'] = [
            'page_last' => $pageCurrent + mt_rand(0, 10),
            'page_current' => $pageCurrent,
            'total' => $pageSize * $pageLast
        ];
    }

    return JsonResponse::create($document);
};

/**
 * Generate a User resource
 *
 * @param Generator $faker
 * @return array
 */
$userCallback = function(Generator $faker) {
    $gender = mt_rand(1, 2);
    return [
        'id'        => $faker->uuid,
        'name'      => $faker->lastName,
        'surname'   => ($gender === 1) ? $faker->firstNameFemale : $faker->firstNameMale,
        'email'     => $faker->email,
        'gender'    => ($gender === 1) ? 'female' : 'male',
        'age'       => mt_rand(18, 99),
    ];
};


/**********************
 * Application routes *
 **********************/

$app = new \Silex\Application();

$app->get('/users/{id}', function (Request $request, $id) use ($generate, $userCallback) {
    return $generate($request, $userCallback);
});

$app->get('/friends/{user_id}', function (Request $request, $user_id) use ($generate, $userCallback) {
    return $generate($request, $userCallback, true);
});

$app->get('/pictures/{user_id}', function(Request $request, $user_id) use ($generate) {

    return $generate($request, function (Generator $faker) {
        return [
            ['id' => $faker->uuid, 'format' => 'large', 'url' => $faker->imageUrl(640, 480, 'people'), 'width' => 640, 'height' => 480],
            ['id' => $faker->uuid, 'format' => 'medium', 'url' => $faker->imageUrl(360, 240, 'people'), 'width' => 320, 'height' => 240],
            ['id' => $faker->uuid, 'format' => 'small', 'url' => $faker->imageUrl(90, 90, 'people'), 'width' => 90, 'height' => 90]
        ];
    });
});

$app->get('pictures/{user_id}/{format}', function (Request $request, $user_id, $format) use ($generate) {
    $formats = [
        'large'     => ['width' => 640, 'height' => 480],
        'medium'    => ['width' => 360, 'height' => 240],
        'small'     => ['width' => 90, 'height' => 90],
    ];

    if (!isset($formats[$format])) {
        return JsonResponse::create(
            ['errors' =>
                [
                    'code' => 'INVALID_FORMAT',
                    'message' => sprintf(
                        '%s is not supported. (supported: %s)',
                        $format,
                        implode(', ', array_keys($formats))
                    ),
                ]
            ],
            400
        );
    }

    return $generate($request, function (Generator $faker) use ($formats, $format) {

        $width = $formats[$format]['width'];
        $height = $formats[$format]['height'];

        return [
            'id' => $faker->uuid,
            'format' => $format,
            'url' => $faker->imageUrl($width, $height, 'people'),
            'width' => $width,
            'height' => $height
        ];
    });

})->value('format', 'medium');


$app->get('/notifications/{user_id}/count', function(Request $request, $user_id) use ($generate) {
    return $generate($request, function(Generator $faker) {

        return [
            'count' => mt_rand(0, 10),
            'last' => (new \DateTime(sprintf('now - %d %s', mt_rand(1, 5), $faker->randomElement(['seconds', 'hours', 'days']))))->format(\DateTime::ATOM)
        ];
    });
});

$app->get('/notifications/{user_id}', function(Request $request, $user_id) use ($generate) {
    return $generate(
        $request,
        function(Generator $faker) {

            $verb = $faker->randomElement(['visit', 'flash', 'message', 'info']);
            $actorDisplayName = $faker->{'firstName' . $faker->randomElement(['Male', 'Female'])} . ' ' . $faker->lastName;
            $targetObjectType = function($verb) {
                switch ($verb) {
                    case 'visit':
                    case 'flash':
                        return 'profile';
                    case 'message':
                    case 'info':
                        return 'post';
                }
            };

            return [
                'verb'      => $verb,
                'published' => $faker->dateTimeBetween('now - 2 hours', 'now')->format(\DateTime::ATOM),
                'title'     => sprintf('new %s from %s', $verb, $actorDisplayName),
                'actor'     => [
                    'id'            => $faker->uuid,
                    'objectType'    => 'person',
                    'displayName'   => $actorDisplayName,
                ],
                'target'    => [
                    'id'            => $faker->uuid,
                    'objectType'    => $targetObjectType($verb),
                    'displayName'   => sprintf("%s's %s", $actorDisplayName, $targetObjectType($verb)),
                ]
            ];
        },
        true
    );
});

$app->run();