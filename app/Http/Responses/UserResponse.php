<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class UserResponse
{
    public function __construct($model)
    {
        $url = env('IMAGE_URL');

        $this->user_details = [
            'user_id' => $model->user_id,
            'username' => $model->username,
        ];
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}