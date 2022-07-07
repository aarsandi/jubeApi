<?php

namespace App\Transformers;

use App\Models\Kecamatan;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\KecamatanResponse;

class KecamatanTransformer extends TransformerAbstract
{
    public function transform(Kecamatan $model)
    {
        $response = new KecamatanResponse($model);

        return $response->serialize();
    }
}