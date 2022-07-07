<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class VillageResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->village_id;
        $this->kecamatan_id = (int)$model->village_kecamatan;
        $this->name = $model->village_name;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}