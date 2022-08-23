<?php

namespace phcode\requests;

use \Exception;
use phcode\infra\Auth;
use phcode\infra\EntityManagerCreator;

class Request
{
    protected $server;
    protected $dataRoute;
    protected $post;
    protected $get;
    private $entityManager;

    public function __construct(?array $data = null)
    {
        $this->server = $_SERVER;
        $this->post = $_POST;
        $this->get = $data["requests"];
        unset($data["requests"]);
        $this->dataRoute = $data;
        $this->entityManager = (new EntityManagerCreator())->getEntityManager();
    }

    public function get(string $param, ?bool $protected = true, ?bool $validToken = true): mixed
    {
        $request_method = strtolower($this->server["REQUEST_METHOD"]);

        $auth = new Auth();
        if($validToken && $request_method == "post" && (!isset($this->post["token"]) || $auth->tokenValidate($this->post["token"]) == false)){
            $_SESSION["errors"] = ["Error of validation form token."];
            return false;
        }
        
        try{
            if(isset($this->$request_method[$param])){
                if($protected) return $this->prepareText($this->$request_method[$param]);
                return $this->$request_method[$param];
            }
            return false;
        } catch (Exception $e) {
            throw new Exception("\nERROR: Request paramter('".$param."') is not exists. \n".$e);
        }
    }

    public function prepareText(string $value): string
    {
        return trim(htmlspecialchars($value));
    }

    public function validate(?array $validation = []): bool
    {
        if(count($validation) == 0) $validation = $this->getValidation();
        $errors = [];
        foreach($validation as $field => $validList){
            $value = $this->get($field);
            if($value === false) return false;
            foreach($validList as $valid){
                switch($valid){
                    case "required":
                        if(strlen($value) == 0 || is_null($value) || $value == "" || !isset($value))
                            $errors[] = "The '".$field."' field is required.";
                        break;
                    case "int":
                        if(!filter_var($value, FILTER_VALIDATE_INT))
                            $errors[] = "The '".$field."' field must be an integer number.";
                        break;
                    case "bool":
                        if(!filter_var($value, FILTER_VALIDATE_BOOL))
                            $errors[] = "The '".$field."' field must be a boolean value.";
                        break;
                    case "email":
                        if(!filter_var($value, FILTER_VALIDATE_EMAIL))
                            $errors[] = "The '".$field."' field must be a valid e-mail.";
                        break;
                    case "domain":
                        if(!filter_var($value, FILTER_VALIDATE_DOMAIN))
                            $errors[] = "The '".$field."' field must be a valid domain.";
                        break;
                    case "float":
                        if(!filter_var($value, FILTER_VALIDATE_FLOAT))
                            $errors[] = "The '".$field."' field must be a float number.";
                        break;
                    case "url":
                        if(!filter_var($value, FILTER_VALIDATE_URL))
                            $errors[] = "The '".$field."' field must be a valid url.";
                        break;                    
                }
                $validParts = explode(":",$valid);
                switch($validParts[0]){
                    case "min":
                        if(strlen($value) < $validParts[1])
                            $errors[] = "The '".$field."' must have in minimum '".$validParts[1]."' characters.";
                        break;
                    case "max":
                        if(strlen($value) > $validParts[1])
                            $errors[] = "The '".$field."' must have in maximum '".$validParts[1]."' characters.";
                        break;
                    case "equals":
                        if(strlen($value) != $validParts[1])
                            $errors[] = "The '".$field."' must have exactly '".$validParts[1]."' characters.";
                        break;
                    case "confirmation":
                        if($this->get($validParts[1]) != $value)
                            $errors[] = "The '".$field."' and '".$validParts[1]."' fields must be equals.";
                        break;
                    case "unique":
                            $className = "phcode\model\\".$validParts[1];
                            $newObj = new $className();
                            $this->repository = $this->entityManager->getRepository(get_class($newObj));
                            $obj = $this->repository->findOneBy([$field => $value]);
                            if(!is_null($obj)){
                                $errors[] = "The '".$field."' alredy exists in our database. Insert a unique value.";
                            }
                        break;
                }
            }
        }
        if(count($errors) > 0){
            $_SESSION["errors"] = $errors;
            return false;
        }
        return true;
    }

}