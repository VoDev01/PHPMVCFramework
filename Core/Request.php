<?php

namespace App\Core;

/**
 * Class describing http request
 */
class Request 
{
    /**
     * Get path of the request without query
     * @return [type]
     */
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
    /**
     * Get method of the request
     * @return [type]
     */
    public function getMethod()
    {
        return $_SERVER['REQUEST_METHOD'];
    }
}