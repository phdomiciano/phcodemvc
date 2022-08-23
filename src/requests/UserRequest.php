<?php

namespace phcode\requests;

class UserRequest extends Request implements RequestInterface
{

    public function getValidation(): mixed
    {
        return [
            "name" => ["required","min:3"],
            "email" => ["email","unique:User"],
            "password" => ["required","min:4"],
            "password_confirmation" => ["confirmation:password"]
        ];
    }

}