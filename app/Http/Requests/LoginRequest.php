<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LoginRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "username" => "required",
            "password" => "required|string",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator, $validator->errors(), 'fail');

        $this->map((object)$data);
    }

    private function map($object)
    {
        $this->username = property_exists($object, 'username') ? $object->username : null;
        $this->password = property_exists($object, 'password') ? $object->password : null;
    }

    public function parse()
    {
        $result = array(
            'username' => $this->username,
            'password' => $this->password,
        );

        return array_filter($result, function ($value) {
            return !empty($value) || $value === 0;
        });
    }
}