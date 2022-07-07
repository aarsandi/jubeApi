<?php

namespace App\Transformers;

use App\Models\ProductUnit;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\ProductUnitResponse;

class ProductUnitTransformer extends TransformerAbstract
{
    public function transform(ProductUnit $model)
    {
        $response = new ProductUnitResponse($model);

        return $response->serialize();
    }
}