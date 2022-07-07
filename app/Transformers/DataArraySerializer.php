<?php

namespace App\Transformers;

use League\Fractal\Resource\NullResource;
use League\Fractal\Serializer\ArraySerializer;

class DataArraySerializer extends ArraySerializer
{
    public function collection($resourceKey, array $data)
    {
        // if(count($data) == 1){            
        //     $value = $data[0];
        // }else{
        //     $value = $data;
        // }
        // if ($resourceKey === false) {return $data;}

        // return array($resourceKey => $value);

        return $data;

    }

    public function item($resourceKey, array $data)
    {
        if ($resourceKey === false) {
            return $data;
        }
        return array($resourceKey ?: 'data' => $data);
    }

    public function null()
    {
        return null;
    }
}