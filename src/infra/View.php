<?php

namespace phcode\infra;

class View
{
    private $file;
    private $title;
    public $alerts;
    private $errors;

    public function __construct(?string $file = null, ?string $title = null)
    {
        $this->setFile($file);
        $this->title = $title;
        $this->errors = [];
        $this->verifyErrors();
        $this->alerts = [];
        $this->verifyAlerts();
    }

    // Put folder.file without extension
    public function setFile(string $fullFile): void
    {
        $file = str_replace(".","/",$fullFile).".php";
        $this->file = $file;
    }

    public function create(?string $file = null, ?string $title = null): void
    {
        $this->file = $file;
        $this->title = $title;
    }

    public function verifyErrors(): void
    {
        if(isset($_SESSION["errors"])){
            $this->errors += $_SESSION["errors"];
            unset($_SESSION["errors"]);
        }
    }

    public function setErrors(array $errors): void
    {
        $this->errors += $errors;
    }

    public function verifyAlerts(): void
    {
        if(isset($_SESSION["alerts"])){
            $this->alerts[] = $_SESSION["alerts"];
            unset($_SESSION["alerts"]);
        }
    }

    static function setAlert(string $style, string $text): void
    {
        $newAlert = [
            "style" => $style,
            "text" => $text
        ];
        $existsAlerts = [];
        if(isset($_SESSION["alerts"])) $existsAlerts = $_SESSION["alerts"];
        $_SESSION["alerts"] = $existsAlerts + $newAlert;
    }

    public function show(?array $variables = null): void
    {
        if(isset($variables)) extract($variables); 
        $auth = new Auth();
        require "view/components/base.php";
    }

}