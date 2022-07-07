<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class RegisterRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "full_name" => "required",
            "username" => "required|unique:user",
            "email" => "required|email|unique:user",
            "password" => "required|min:4",
            "user_category" => "required",
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
        $this->full_name = property_exists($object, 'full_name') ? $object->full_name : null;
        $this->username = property_exists($object, 'username') ? $object->username : null;
        $this->email = property_exists($object, 'email') ? $object->email : null;
        $this->password = property_exists($object, 'password') ? $object->password : null;
        $this->user_category = property_exists($object, 'user_category') ? $object->user_category : null;
        $this->kabupaten = property_exists($object, 'kabupaten') ? $object->kabupaten : null;
        $this->kecamatan = property_exists($object, 'kecamatan') ? $object->kecamatan : null;
        $this->village = property_exists($object, 'village') ? $object->village : null;
    }

    public function parse()
    {
        $result = array(
            'full_name' => $this->full_name,
            'username' => $this->username,
            'email' => $this->email,
            'password' => $this->password,
            'user_category' => $this->user_category,
            'kabupaten' => $this->kabupaten,
            'kecamatan' => $this->kecamatan,
            'village' => $this->village,
        );

        return array_filter($result, function ($value) {
            return !empty($value) || $value === 0;
        });
    }
}