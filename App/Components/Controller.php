<?php

namespace App\Components;

class Controller implements ControllerInterface
{
    protected $view;


    public function __construct()
    {
        $this->view = new View();
    }


    public function index()
    {
        die('controller not implemented');
    }


    public function before()
    {
    }


    public function after()
    {
        $this->view->render();
    }
}