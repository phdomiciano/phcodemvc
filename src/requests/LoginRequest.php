<?php

namespace phcode\requests;

class LoginRequest extends Request implements RequestInterface
{

    public function getValidation(): mixed
    {
        return [
            "email" => ["email"],
            "password" => ["required"]
        ];
    }

}