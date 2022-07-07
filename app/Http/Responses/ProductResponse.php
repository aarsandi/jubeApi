<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;

class ProductResponse
{
    public function __construct($model)
    {
        $this->id = (int)$model->product_id;
        $this->name = $model->product_name;
        $this->price = $model->product_price;
        $this->slug = $model->product_slug;
        $this->category_name = $model->category_name;
        $this->description = $model->product_description;
        $this->image = $this->image($model->product_image);
    }


    public function image($product_image)
    {
        $url_image = env('PRODUCT_URL');
        $url = array();

        if($product_image != '' || $product_image != null){
            $explode = explode(",", $product_image);
            foreach($explode as $key => $val){
                $url[] = ['url' => $url_image.$val];
            }
        }else {
            $url = array('url' => 'http://127.0.0.1/RURAL-BE-FE/assets/images/noImage.jpg');
        }

        return $url;
    }

    public function serialize()
    {
        return get_object_vars($this);
    }
}