<?php

namespace phcode\controller;

use phcode\model\User;
use phcode\infra\EntityManagerCreator;
use phcode\infra\View;
use phcode\infra\Auth;

class LoginController extends Controller
{
    private $user;
    private $userRepository;

    public function index(): void
    {
        $view = new View("login.index", "Courses Manager");
        $view->show();
    }

    public function store(): void
    {
        if(!$this->request->validate()){
            header("Location: /login/index");
            return;
        }
        $email = $this->request->get("email");
        $password = $this->request->get("password");
        $this->userRepository = $this->entityManager->getRepository(User::class);
        $this->user = $this->userRepository->findOneBy(["email" => $email]);
        if(is_null($this->user) || !$this->user->passwordVerify($password)){
            View::setAlert("danger","User and password not found.");
            header("Location: /login/index");
            return;
        }
        else{
            Auth::create($this->user);
            header("Location: /courses/index");
            return;
        }
    }

    public function destroy(): void
    {
        Auth::destroy();
        header("Location: /login/index");
        return;
    }

}