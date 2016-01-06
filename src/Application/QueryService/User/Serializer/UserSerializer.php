<?php
namespace Application\QueryService\User\Serializer;

use Document\Serializer;

class UserSerializer extends Serializer
{
    public function getAttributes($model, array $fields = null)
    {
        return [
            'name' => $model['name'],
            'surname' => $model['surname'],
            'gender' => $model['gender'],
            'age' => $model['age'],
            'email' => $model['email']
        ];
    }

    public function getId($model)
    {
        return $model['id'];
    }

    public function getType($model)
    {
        return 'users';
    }

}