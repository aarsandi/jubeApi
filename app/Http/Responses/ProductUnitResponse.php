<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ProductUnitResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->product_unit_id;
        $this->name = $model->product_unit_name;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}