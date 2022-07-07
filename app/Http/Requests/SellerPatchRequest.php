<?php

namespace App\Http\Requests;

use App\Models\Seller;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerPatchRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "store_name" => "required",
            "seller_name" => "required",
            "seller_address" => "required",
            "seller_phone" => "required",
            "seller_email" => "required",
            "kabupaten" => "required",
            "kecamatan" => "required",
            "village" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator, $validator->errors(), 'fail');

        $this->map((object)$data);
    }

    private function map($object)
    {
        $this->store_name = property_exists($object, 'store_name') ? $object->store_name : null;
        $this->seller_name = property_exists($object, 'seller_name') ? $object->seller_name : null;
        $this->seller_address = property_exists($object, 'seller_address') ? $object->seller_address : null;
        $this->seller_phone = property_exists($object, 'seller_phone') ? $object->seller_phone : null;
        $this->seller_email = property_exists($object, 'seller_email') ? $object->seller_email : null;
        $this->kabupaten = property_exists($object, 'kabupaten') ? $object->kabupaten : null;
        $this->kecamatan = property_exists($object, 'kecamatan') ? $object->kecamatan : null;
        $this->village = property_exists($object, 'village') ? $object->village : null;

        $this->modified_user = Auth::user()->user_id;
        $this->modified_date = date('Y-m-d H:i:s');

    }

    public function parse()
    {
        $result = array(
            'store_name' => $this->store_name,
            'seller_name' => $this->seller_name,
            'seller_phone' => $this->seller_phone,
            'seller_email' => $this->seller_email,
            'seller_address' => $this->seller_address,
            'kabupaten' => $this->kabupaten,
            'kecamatan' => $this->kecamatan,
            'village' => $this->village,
            'modified_user' => $this->modified_user,
            'modified_date' => $this->modified_date,
        );

        return array_filter($result, function ($value) {
            return !empty($value);
        });
    }
}