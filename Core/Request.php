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

    public function is(string $method)
    {
        return $this->method() === $method;
    }

    public function isGet()
    {
        return $this->method() === 'get';
    }

    public function isPost()
    {
        return $this->method() === 'Post';
    }

    public function body()
    {
        $body = [];
        if($this->method() === 'get')
        {
            foreach($_GET as $key => $value)
            {
                $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->method() === 'post')
        {
            foreach($_POST as $key => $value)
            {
                $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        return $body;
    }
}