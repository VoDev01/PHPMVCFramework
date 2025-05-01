<?php

namespace App\Core;

class Request 
{
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'];
        $questionPos = strpos($path, '?');
        if($questionPos)
        {
            $path = substr($path, 0, $questionPos);
        }
        return $path;
    }
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}