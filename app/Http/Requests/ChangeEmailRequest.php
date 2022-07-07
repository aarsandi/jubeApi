<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ChangeEmailRequest
{
    public function __construct($data)
    {
        if (empty($data)) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Request was empty');
        }

        $validator = Validator::make($data, [
            "id" => "required|integer",
            "email" => "required",
        ]);

        if ($validator->fails())
            throw new ValidationException($validator, $validator->errors(), 'fail');

        $this->map((object)$data);
    }

    private function map($object)
    {
        $this->user_id = property_exists($object, 'id') ? $object->id : null;
        $this->email = property_exists($object, 'email') ? $object->email : null;
    }

    public function parse()
    {
        $result = array(
            'user_id' => $this->user_id,
            'email' => $this->email,
        );

        return $result;

    }
}