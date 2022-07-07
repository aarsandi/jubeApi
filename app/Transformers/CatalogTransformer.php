<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\CatalogResponse;

class CatalogTransformer extends TransformerAbstract
{
    public function transform(User $model)
    {
        $response = new CatalogResponse($model);

        return $response->serialize();
    }
}