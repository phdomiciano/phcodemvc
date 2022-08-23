<?php

namespace phcode\requests;

class CoursesRequest extends Request implements RequestInterface
{

    public function getValidation(): mixed
    {
        return [
            "name" => ["required","min:3"],
            "description" => ["required"]
        ];
    }

}