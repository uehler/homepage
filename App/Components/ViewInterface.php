<?php

namespace App\Components;

interface ViewInterface
{
    public function render();


    public function assign(string $key, $value);


    public function addTemplate(string $template);


    public function cacheWarmup();
}