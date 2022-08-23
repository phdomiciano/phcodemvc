<?php

namespace phcode\infra;

use phcode\model\User;
use phcode\infra\EntityManagerCreator;

class Auth
{
    public $user;
    private $token;
    private $last_token;

    public function __construct(?User $user = null)
    {
        if(!is_null($user)){
            $this->$user = $user;
            $_SESSION["user"] = $user;
        }
        else if(isset($_SESSION["user"])){
            $this->user = $_SESSION["user"];
        }

        if(isset($_SESSION["token"])){
            $this->token = $_SESSION["token"];
            if(isset($_SESSION["last_token"])){
                $this->last_token = $_SESSION["last_token"];
            }
        }
    }

    public function user($entityManager): User
    {
        return $entityManager->find(User::class, $this->user->getId());
    }

    static function create(User $user): void
    {
        $_SESSION["user"] = $user;
    }

    static function destroy(): void
    {
        unset($_SESSION["user"]);
    }

    static function validate(): bool
    {
        if(isset($_SESSION["user"])){
            $user = $_SESSION["user"];
            if(is_object($user) && (get_class($user) == User::class)) return true;
        }
        return false;
    }

    static function destroyToken(): void
    {
        unset($_SESSION["token"]);
        unset($_SESSION["last_token"]);
    }

    public function createToken(): void
    {
        if(isset($_SESSION["token"])){
            $this->last_token = $_SESSION["token"];
            $_SESSION["last_token"] = $this->last_token;
        }
        $this->token = $this->keyGenerator();
        $_SESSION["token"] = $this->token;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    private function keyGenerator(): string
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $codeLength = 15;
        $code = "";
        for($i = 1; $i <= $codeLength; $i++){
            $number = rand(0,(strlen($chars)-1));
            $code .= substr($chars,$number,1);
        }
        return md5($code);
    }

    public function tokenValidate(string $tokenForm): bool
    {
        //echo $this->token." <<>> ".$this->last_token." enviado: ".$tokenForm."<br />";
        if($this->token == $tokenForm || $this->last_token == $tokenForm) return true;
        return false;
    }

    public function getTokenHidden(): string
    {
        return '<input type="hidden" name="token" value="'.$this->token.'">';
    }

    public function token(): string
    {
        return $this->getTokenHidden();
    }

    public function verifyOwner(object|null $obj){
        if(is_null($obj) || ($obj->getUserId() != $this->user->getId())){
            header("Location: /");
            return;
        }
        return true;
    }

}