<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "category_id" => "required|exists:category,category_id",
            "seller_id" => "required|exists:seller,seller_id",
            "product_name" => "required|unique:product,product_name",
            "product_price" => "required",
            "price_seller" => "required",
            "product_stock" => "required|integer",
            "product_weight" => "required|integer",
            "product_long" => "required|integer",
            "product_wide" => "required|integer",
            "product_high" => "required|integer",
            "product_unit" => "required|exists:product_unit,product_unit_id",
            "product_description" => "required|min:30",

            "product_image_1" => "required|image|mimes:jpeg,png,jpg,gif,svg,webp",
            "product_image_2" => "image|mimes:jpeg,png,jpg,gif,svg,webp",
            "product_image_3" => "image|mimes:jpeg,png,jpg,gif,svg,webp",
            "product_image_4" => "image|mimes:jpeg,png,jpg,gif,svg,webp",
            "product_image_5" => "image|mimes:jpeg,png,jpg,gif,svg,webp",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator, $validator->errors(), 'fail');

        $this->map((object)$data);
    }

    private function map($object)
    {
        $this->category_id = property_exists($object, 'category_id') ? $object->category_id : null;
        $this->seller_id = property_exists($object, 'seller_id') ? $object->seller_id : null;
        $this->product_name = property_exists($object, 'product_name') ? $object->product_name : null;
        $this->product_price = property_exists($object, 'product_price') ? $object->product_price : null;
        $this->price_seller = property_exists($object, 'price_seller') ? $object->price_seller : null;
        $this->product_stock = property_exists($object, 'product_stock') ? $object->product_stock : null;
        $this->product_weight = property_exists($object, 'product_weight') ? $object->product_weight : null;
        $this->product_long = property_exists($object, 'product_long') ? $object->product_long : null;
        $this->product_wide = property_exists($object, 'product_wide') ? $object->product_wide : null;
        $this->product_high = property_exists($object, 'product_high') ? $object->product_high : null;
        $this->product_unit = property_exists($object, 'product_unit') ? $object->product_unit : null;
        $this->product_description = property_exists($object, 'product_description') ? $object->product_description : null;
        
        $this->image_exist = property_exists($object, 'product_image_1') ? true : false;
        $this->product_image_1 = property_exists($object, 'product_image_1') ? $object->product_image_1 : null;
        $this->product_image_2 = property_exists($object, 'product_image_2') ? $object->product_image_2 : null;
        $this->product_image_3 = property_exists($object, 'product_image_3') ? $object->product_image_3 : null;
        $this->product_image_4 = property_exists($object, 'product_image_4') ? $object->product_image_4 : null;
        $this->product_image_5 = property_exists($object, 'product_image_5') ? $object->product_image_5 : null;

        $this->currency = 'Rp';
        $this->user_id = Auth::user()->user_id;
        $this->user_guid = Auth::user()->user_guid;
        $this->seller_guid = Seller::getSellerGuid($object->seller_id);
        $this->product_guid = md5(time() . Auth::user()->user_id . $object->product_name);
        $this->product_slug = Str::slug($object->product_name);
        $this->created_user = Auth::user()->user_id;
        $this->created_date = date('Y-m-d H:i:s');

    }

    public function parse()
    {
        $result = array(
            'currency' => $this->currency,
            'user_id' => $this->user_id,
            'user_guid' => $this->user_guid,
            'seller_id' => $this->seller_id,
            'seller_guid' => $this->seller_guid,
            'category_id' => $this->category_id,
            'product_guid' => $this->product_guid,
            'product_slug' => $this->product_slug,
            'product_name' => $this->product_name,
            'product_price' => $this->product_price,
            'price_seller' => $this->price_seller,
            'product_stock' => $this->product_stock,
            'product_weight' => $this->product_weight,
            'product_long' => $this->product_long,
            'product_wide' => $this->product_wide,
            'product_high' => $this->product_high,
            'product_unit' => $this->product_unit,
            'product_description' => $this->product_description,
            'created_user' => $this->created_user,
            'created_date' => $this->created_date,
            
            'image_exist' => $this->image_exist,
            'product_image_1' => $this->product_image_1,
            'product_image_2' => $this->product_image_2,
            'product_image_3' => $this->product_image_3,
            'product_image_4' => $this->product_image_4,
            'product_image_5' => $this->product_image_5,
        );

        return $result;
    }
}