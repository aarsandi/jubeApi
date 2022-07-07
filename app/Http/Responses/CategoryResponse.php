<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class CategoryResponse
{
    public function __construct($model)
    {
        $url_image = env('CATEGORY_URL');

        $this->id = (int)$model->category_id;
        $this->name = $model->category_name;
        $this->slug = $model->category_slug;
        $this->image = $model->category_image ? $url_image.$model->category_image : null;
        $this->description = $model->category_description;
    }


    public function serialize()
    {
        return get_object_vars($this);
    }
}