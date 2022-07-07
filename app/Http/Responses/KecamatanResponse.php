<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class KecamatanResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->kecamatan_id;
        $this->kabupaten_id = (int)$model->kabupaten_id;
        $this->name = $model->kecamatan_name;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}