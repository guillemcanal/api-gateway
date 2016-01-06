<?php

namespace Application\QueryService\Picture\Serializer;

use Document\Serializer;

class PictureSerializer extends Serializer
{
    public function getType($model)
    {
        return 'pictures';
    }

    public function getId($model)
    {
        return $model['id'];
    }

    /**
     * @param mixed $model
     * @param array|null $fields
     * @return array
     */
    public function getAttributes($model, array $fields = null)
    {
        return [
            'format' => $model['format'],
            'url' => $model['url'],
            'width' => $model['width'],
            'height' => $model['height']
        ];
    }

}