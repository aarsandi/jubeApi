<?php

namespace App\Http\Responses\Management;

use Carbon\Carbon;
use App\Models\ProductMarketplace;
use Illuminate\Support\Facades\URL;

class ProductDetailResponse
{
    public function __construct($model)
    {               
        $this->id = $model->product_id;
        $this->seller_id = $model->seller_id;
        $this->category_id = $model->category_id;
        $this->name = $model->product_name;
        $this->price = $model->product_price;
        $this->price_seller = $model->price_seller;
        $this->unit = $model->product_unit;
        $this->stock = $model->product_stock;
        $this->weight = $model->product_weight;
        $this->long = $model->product_long;
        $this->wide = $model->product_wide;
        $this->high = $model->product_high;
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