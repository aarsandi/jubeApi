<?php

namespace App\Transformers;

use App\Models\Category;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\CategoryResponse;

class CategoryTransformer extends TransformerAbstract
{
    public function transform(Category $model)
    {
        $response = new CategoryResponse($model);

        return $response->serialize();
    }
}