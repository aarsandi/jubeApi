<?php

namespace App\Http\Requests;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserPatchRequest
{
    public function __construct($data)
    {

        $validator = Validator::make($data, [
            'id' => 'integer',
            'first_name' => 'max:200',
            'last_name' => 'max:200',
            'email' => 'email|unique:users',
            'password' => 'max:200'
        ]);

        if ($validator->fails())
            throw new ValidationException($validator, $validator->errors(), 'fail');
            
        $this->map((object)$data);
    }
    private function map($object)
    {
        $this->id = property_exists($object, 'id') ? $object->id : null;
        $this->first_name = property_exists($object, 'first_name') ? $object->first_name : null;
        $this->last_name = property_exists($object, 'last_name') ? $object->last_name : null;
        $this->email = property_exists($object, 'email') ? $object->email : null;
        $this->password = property_exists($object, 'password') ? $object->password : null;
    }

    public function parse()
    {
        $result = array(
            'id' => auth()->user()->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'password' => $this->password
        );

        return $result;
    }
}