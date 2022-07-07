<?php

namespace App\Transformers\Management;

use App\Models\Seller;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\Management\SellerDetailResponse;

class SellerDetailTransformer extends TransformerAbstract
{
    public function transform(Seller $model)
    {
        $response = new SellerDetailResponse($model);

        return $response->serialize();
    }
}