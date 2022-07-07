<?php

namespace App\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\ProductDetailResponse;

class ProductDetailTransformer extends TransformerAbstract
{
    public function transform(Product $model)
    {
        $response = new ProductDetailResponse($model);

        return $response->serialize();
    }
}