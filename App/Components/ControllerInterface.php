<?php

namespace App\Components;

interface ControllerInterface
{
    public function index();


    public function before();


    public function after();
}