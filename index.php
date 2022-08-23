<?php

require __DIR__ . '/vendor/autoload.php';

use phcode\infra\Route;
use phcode\requests\Request;
use phcode\infra\Auth;

session_start();

$namespaceControllerDefault = "phcode\\controller\\";
$namespaceRequestDefault = "phcode\\requests\\";

$request_method = $_SERVER["REQUEST_METHOD"];

if(isset($_SERVER["PATH_INFO"])){
    $url = $_SERVER["PATH_INFO"];
}
else {
    $url = "/";
}

$auth = new Auth();
$auth->createToken();
if(Auth::validate() && ($url == "/" || $url == "/login/index")){
    $url = "/courses/index";
}

$routes = require __DIR__. '/config/routes.php';
$route = new Route($routes);
$route->setParamters($url, $request_method);
$routeParamters = $route->getRoute();

if(!$routeParamters){
    http_response_code(404);
    die();
}

if(!Auth::validate() && $routeParamters["auth"] === true){
    header("Location: /");
    exit();
}

$controller = $namespaceControllerDefault.$routeParamters["controller"];
$method = $routeParamters["method"];
$requestClass = $namespaceRequestDefault.$routeParamters["request_class"];

$request = new $requestClass($routeParamters);
$control = new $controller($request);
$control->$method();