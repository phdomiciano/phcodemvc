<?php

namespace phcode\infra;

class Route
{
    public $routes;
    public $gets;
    public $posts;
    public $url;
    public $fullURL;
    public $request_method;
    public $requestsURL;

    public function __construct(?array $routes = null)
    {
        if(!is_null($routes)){
            $this->setRouteList($routes);
        }
    }

    public function setRouteList(array $routes): void
    {
        $this->routes = $routes;
        if(isset($routes["GET"])){
            $this->gets = $this->deleteRequestsOfURLs($routes["GET"]);
        }
        if(isset($routes["POST"])){
            $this->posts = $this->deleteRequestsOfURLs($routes["POST"]);
        }
    }

    public function deleteRequestsOfURLs(array $list): array
    {
        $newList = [];
        foreach($list as $index => $item){
            $requests = [];
            preg_match_all('/\/{[A-z0-9]*}/', $index, $requests);
            $newRequests = preg_replace('/\/||\{||\}/', '', $requests[0]);
            $newItem = [$item,$newRequests];
            $newList[$this->getUrlWithoutGET($index)] = $newItem;
        }
        return $newList;
    }

    public function getUrlWithoutGET(?string $url = null): string
    {
        if(is_null($url)) $url = $this->fullURL;
        return preg_replace('/\/{[A-z0-9]*}/', '', $url);
    }

    public function setParamters(string $fullURL, string $request_method): void
    {
        $this->fullURL = $fullURL;
        $this->request_method = $request_method;
    }

    public function getRoute(?string $fullURL = null, ?string $request_method = null): bool|array
    {
        if(is_null($fullURL)) $fullURL = $this->fullURL;
        if(is_null($request_method)) $request_method = $this->request_method;

        $list = [];
        if($request_method == "GET"){
            $list = $this->gets;
        }
        else if($request_method == "POST"){
            $list = $this->posts;
        }

        $findKey = false;
        $requestsComplete = [];
        $url = "";
        foreach($list as $index => $item){
            if($fullURL == $index){
                $findKey = true;
                $url = $index;
                break;
            }
            $requestsURLClient = explode("/",str_replace($index."/", '', $fullURL));
            if(count($requestsURLClient) == count($item[1])){
                $url = $index;
                $findKey = true;
                foreach($requestsURLClient as $i => $res){
                    $requestsComplete[$item[1][$i]] = $res;
                }
                break;
            }
        }

        if($findKey === false){
            return false;
        }

        $auth = true;
        if(isset($list[$url][0][2])){
            $auth = $list[$url][0][2];
        }

        $request_class = "Request";
        $possibleRequestClass = str_replace("Controller", "Request", $list[$url][0][0]);
        $possibleRequestFile = __DIR__."/../requests/".$possibleRequestClass.".php";
        if(isset($list[$url][0][3])){
            $request_class = $list[$url][0][3];
        } else if(file_exists($possibleRequestFile)){
            $request_class = $possibleRequestClass;
        }

        return [
            "url" => $url,
            "controller" => $list[$url][0][0],
            "method" => $list[$url][0][1],
            "auth" => $auth,
            "requests" => $requestsComplete,
            "request_class" => $request_class
        ];
    }

}