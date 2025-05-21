<?php

namespace App\Core;

use UnexpectedValueException;

/**
 * Class that represents http request as OOP class
 */
#[\AllowDynamicProperties]
class Request 
{
    public function __construct()
    {
        if($this->isGet())
        {
            foreach($_GET as $key => $value)
            {
                $this->{$key} = filter_input(INPUT_GET, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
        if($this->isPost())
        {
            foreach($_POST as $key => $value)
            {
                $this->{$key} = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
            }
        }
    }
    public function __get(string $name)
    {
        return $this->{$name};
    }
    /**
     * Get path of the request without query
     * @return string
     */
    public function path(): string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if($path === false)
        {
            throw new UnexpectedValueException("Malformed URL: '{$_SERVER["REQUEST_URI"]}'");
        }
        return $path;
    }
    /**
     * Get method of the request
     * @return string
     */
    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function isMethod(string $method): bool
    {
        return $this->method() === $method;
    }

    public function isGet(): bool
    {
        return $this->isMethod('get');
    }

    public function isPost(): bool
    {
        return $this->isMethod('post');
    }

    public function body(): array
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