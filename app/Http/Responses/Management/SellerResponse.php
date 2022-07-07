<?php

namespace App\Http\Responses\Management;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class SellerResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->seller_id;
        $this->name = $model->seller_name;
        $this->store_name = $model->store_name;
        $this->email = $model->seller_email;
        $this->phone_number = $model->seller_phone;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}