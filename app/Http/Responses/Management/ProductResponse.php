<?php

namespace App\Http\Responses\Management;

use Carbon\Carbon;
use App\Models\ProductMarketplace;
use Illuminate\Support\Facades\URL;

class ProductResponse
{
    public function __construct($model)
    {
        $url_marketplace = $this->url_marketplace($model->status_sync_tokopedia, $model->status_sync_shopee,$model->product_id); 

        $this->id = (int)$model->product_id;
        $this->name = $model->product_name;
        $this->price = $model->product_price;
        $this->unit = $model->product_unit_name;
        $this->stock = $model->product_stock;
        $this->weight = $model->product_weight;
        $this->slug = $model->product_slug;
        $this->seller_name = $model->seller_name;
        $this->store_name = $model->store_name;
        $this->image = $this->image($model->product_image);
        $this->tokopedia_url = $url_marketplace['tokopedia'];
        $this->shopee_url = $url_marketplace['shopee'];
        $this->lazada_url = null;
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

        return $url[0];
    }
    
    public function url_marketplace($status_sync_tokopedia, $status_sync_shopee, $product_id)
    {
        if ($status_sync_tokopedia == 1 || $status_sync_shopee == 1){
            $data = ProductMarketplace::getProductURL($product_id);
        }else { $data = array('tokopedia' => null, 'shopee' => null); }

        return $data;
    }

    public function serialize()
    {
        return get_object_vars($this);
    }
}