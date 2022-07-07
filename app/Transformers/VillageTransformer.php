<?php

namespace App\Transformers;

use App\Models\Village;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\VillageResponse;

class VillageTransformer extends TransformerAbstract
{
    public function transform(Village $model)
    {
        $response = new VillageResponse($model);

        return $response->serialize();
    }
}