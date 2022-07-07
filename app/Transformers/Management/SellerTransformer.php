<?php

namespace App\Transformers\Management;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\Management\SellerResponse;

class SellerTransformer extends TransformerAbstract
{
    public function transform(Seller $model)
    {
        $response = new SellerResponse($model);

        return $response->serialize();
    }
}