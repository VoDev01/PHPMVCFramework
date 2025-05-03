<?php

namespace App\Core;

class Response
{
    public function getResponseCode()
    {
        return http_response_code();
    }
    public function setResponseCode(int $code)
    {
        return http_response_code($code);
    }
}