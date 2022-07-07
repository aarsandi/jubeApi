<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\ProductResponse;

class ProductTransformer extends TransformerAbstract
{
    public function transform(Product $model)
    {
        $response = new ProductResponse($model);

        return $response->serialize();
    }
}