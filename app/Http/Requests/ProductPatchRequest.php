<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductPatchRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "product_id" => "exists:product,product_id,user_id,".Auth::user()->user_id,
            "category_id" => "exists:category,category_id",
            "product_name" => "unique:product,product_name",
            "product_price" => "",
            "price_seller" => "",
            "product_stock" => "integer",
            "product_weight" => "integer",
            "product_long" => "integer",
            "product_wide" => "integer",
            "product_high" => "integer",
            "product_unit" => "exists:product_unit,product_unit_id",
            "product_description" => "min:30",

            "product_image_1" => "image|mimes:jpeg,png,jpg,gif,svg,webp",
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
        $this->product_id = property_exists($object, 'product_id') ? $object->product_id : null;
        $this->category_id = property_exists($object, 'category_id') ? $object->category_id : false;
        $this->product_name = property_exists($object, 'product_name') ? $object->product_name : false;
        $this->product_price = property_exists($object, 'product_price') ? $object->product_price : false;
        $this->price_seller = property_exists($object, 'price_seller') ? $object->price_seller : false;
        $this->product_stock = property_exists($object, 'product_stock') ? $object->product_stock : false;
        $this->product_weight = property_exists($object, 'product_weight') ? $object->product_weight : false;
        $this->product_long = property_exists($object, 'product_long') ? $object->product_long : false;
        $this->product_wide = property_exists($object, 'product_wide') ? $object->product_wide : false;
        $this->product_high = property_exists($object, 'product_high') ? $object->product_high : false;
        $this->product_unit = property_exists($object, 'product_unit') ? $object->product_unit : false;
        $this->product_description = property_exists($object, 'product_description') ? $object->product_description : false;
        
        $this->image_exist = property_exists($object, 'product_image_1') ||  property_exists($object, 'product_image_2') ||  property_exists($object, 'product_image_3') ||  property_exists($object, 'product_image_4') ||  property_exists($object, 'product_image_5') ? true : null;
        $this->product_image_1 = property_exists($object, 'product_image_1') ? $object->product_image_1 : null;
        $this->product_image_2 = property_exists($object, 'product_image_2') ? $object->product_image_2 : null;
        $this->product_image_3 = property_exists($object, 'product_image_3') ? $object->product_image_3 : null;
        $this->product_image_4 = property_exists($object, 'product_image_4') ? $object->product_image_4 : null;
        $this->product_image_5 = property_exists($object, 'product_image_5') ? $object->product_image_5 : null;

        $this->modified_user = Auth::user()->user_id;
        $this->modified_date = date('Y-m-d H:i:s');

    }

    public function parse()
    {
        $result = array(
            'product_id' => $this->product_id,
            'category_id' => $this->category_id,
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
            'modified_user' => $this->modified_user,
            'modified_date' => $this->modified_date,
            
            'image_exist' => $this->image_exist,
            'product_image_1' => $this->product_image_1,
            'product_image_2' => $this->product_image_2,
            'product_image_3' => $this->product_image_3,
            'product_image_4' => $this->product_image_4,
            'product_image_5' => $this->product_image_5,
        );


        return array_filter($result, function ($value) {
            return !empty($value) || $value === null;
        });
    }
}