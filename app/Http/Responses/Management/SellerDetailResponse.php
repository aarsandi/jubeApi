<?php

namespace App\Http\Responses\Management;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class SellerDetailResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->seller_id;
        $this->name = $model->seller_name;
        $this->store_name = $model->store_name;
        $this->address = $model->seller_address;
        $this->email = $model->seller_email;
        $this->phone_number = $model->seller_phone;
        $this->kabupaten = (int)$model->kabupaten;
        $this->kecamatan = (int)$model->kecamatan;
        $this->village = (int)$model->village;
        $this->bank = $model->getBank($model->seller_guid);
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}