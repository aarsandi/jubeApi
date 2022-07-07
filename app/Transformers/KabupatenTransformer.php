<?php

namespace App\Transformers;

use App\Models\Kabupaten;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\KabupatenResponse;

class KabupatenTransformer extends TransformerAbstract
{
    public function transform(Kabupaten $model)
    {
        $response = new KabupatenResponse($model);

        return $response->serialize();
    }
}