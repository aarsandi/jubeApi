<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class KabupatenResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->kabupaten_id;
        $this->name = $model->kabupaten_name;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}