<?php

namespace App\Http\Responses;

use Carbon\Carbon;
use App\Models\ProductMarketplace;
use Illuminate\Support\Facades\URL;

class ProductDetailResponse
{
    public function __construct($model)
    {
        $url_marketplace = $this->url_marketplace($model->status_sync_tokopedia, $model->status_sync_shopee,$model->product_id); 

        $this->category = [
            'id' => $model->category_id,
            'name' => $model->category_name,
        ];
               
        $this->product = [
            'id' => $model->product_id,
            'name' => $model->product_name,
            'price' => $model->product_price,
            'unit' => $model->product_unit_name,
            'stock' => $model->product_stock,
            'sold' => 0,
            'description' => $model->product_description,
            'image' => $this->image($model->product_image),
            'tokopedia_url' => $url_marketplace['tokopedia'],
            'shopee_url' => $url_marketplace['shopee'],
            'lazada_url' => null,
        ];
       
        $this->user = [
            'id' => $model->user_id,
            'name' => $model->full_name,
            'address' => $model->getSellerAddress($model->seller_id),
            'catalog_name' => $model->catalog_name,
        ];

    }


    public function url_marketplace($status_sync_tokopedia, $status_sync_shopee, $product_id)
    {
        if ($status_sync_tokopedia == 1 || $status_sync_shopee == 1){
            $data = ProductMarketplace::getProductURL($product_id);
        }else { $data = array('tokopedia' => null, 'shopee' => null); }

        return $data;
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