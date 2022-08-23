<?php

namespace phcode\controller;

use phcode\infra\EntityManagerCreator;
use phcode\infra\View;
use phcode\requests\Request;

class Controller
{
    protected $entityManager;
    protected $request;

    public function __construct(Request $request)
    {
        $this->entityManager = (new EntityManagerCreator())->getEntityManager();
        $this->request = $request;
    }
}