<?php

// "url" => ["controller", "method", AUTH(true or false), RequestClass]
// AUTH = Default is true
// RequestClass = Default is "Request"(Parent class) or a class with the same name of controller that exists in src/requests folder, like "UserRequest" if the controller is "UserController"
// AUTH and RequestClass are both optionals

return [
    "GET" => [
        "/" => [
            "LoginController", 
            "index", 
            false
        ],
        "/login/index" => [
            "LoginController", 
            "index", 
            false
        ],
        "/login/destroy" => [
            "LoginController", 
            "destroy"
        ],
        "/courses/index" => [
            "CoursesController", 
            "index"
        ],
        "/courses/create" => [
            "CoursesController", 
            "create"
        ],
        "/courses/edit/{id}" => [
            "CoursesController", 
            "edit"
        ],
        "/courses/json" => [
            "CoursesController", 
            "showJSON"
        ]    
    ],
    "POST" => [
        "/login/store" => [
            "LoginController", 
            "store", 
            false
        ],
        "/user/store" => [
            "UserController", 
            "store", 
            false
        ],
        "/courses/store" => [
            "CoursesController", 
            "store"
        ],
        "/courses/update" => [
            "CoursesController", 
            "update"
        ],
        "/courses/json" => [
            "CoursesController", 
            "showJSON"
        ],
        "/courses/destroy" => [
            "CoursesController", 
            "destroy"
        ]
    ]
];