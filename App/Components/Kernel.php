<?php

namespace App\Components;

use App\Controllers\Index;

class Kernel implements KernelInterface
{
    /**
     * this loads the page
     * TODO: if page grows, load controller dynamical
     */
    public function load()
    {
        $controller = new Index();
        $controller->before();
        $controller->index();
        $controller->after();
    }
}