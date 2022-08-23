<?php

namespace phcode\controller;

use phcode\model\User;
use phcode\infra\EntityManagerCreator;
use phcode\infra\Auth;

class UserController extends Controller
{
    private $userRepository;

    public function store(): void
    {
        if(!$this->request->validate()){
            header("Location: /login/index");
            return;
        }
        $user = new User();
        $user->setName($this->request->get("name"));
        $user->setEmail($this->request->get("email"));
        $user->setPassword($this->request->get("password"));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        Auth::create($user);
        header("Location: /courses/index");
        return;
    }

}