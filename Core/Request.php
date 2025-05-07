<?php

namespace App\Core;

/**
 * Class describing http request
 */
class Request 
{
    public function __construct() {
        if($this->isGet())
        {
            foreach($_GET as $field)
            {
                $this->{$field} = filter_input(INPUT_GET, $field, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->isPost())
        {
            foreach($_POST as $field)
            {
                $this->{$field} = filter_input(INPUT_POST, $field, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
    /**
     * Get path of the request without query
     * @return [type]
     */
    public function path()
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
    public function method()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isMethod(string $method)
    {
        return $this->method() === $method;
    }

    public function isGet()
    {
        return $this->isMethod('get');
    }

    public function isPost()
    {
        return $this->isMethod('post');
    }

    public function body()
    {
        $body = [];
        if($this->isGet())
        {
            foreach($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->isPost())
        {
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}