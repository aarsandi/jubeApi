<?php

namespace App\Transformers;

use App\Models\User;
use League\Fractal\TransformerAbstract;
use App\Http\Responses\UserResponse;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $model)
    {
        $response = new UserResponse($model);

        return $response->serialize();
    }
}