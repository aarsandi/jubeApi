<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class CatalogResponse
{
    public function __construct($model)
    {
        $url_image = env('IMAGE_URL');

        $this->id = $model->user_id;
        $this->name = $model->full_name;
        $this->catalog_name = $model->catalog_name;
        $this->image = $model->catalog_picture != '' ? $url_image.$model->catalog_picture : $url_image.'banner_blue_landscape.png';
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}