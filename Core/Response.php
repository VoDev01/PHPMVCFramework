<?php

namespace App\Core;

/**
 * Class describing http response
 */
class Response
{
    /**
     * Get current response http code
     * @return [type]
     */
    public function getResponseCode()
    {
        return http_response_code();
    }
    /**
     * Sets http response code of the current response
     * @param int $code
     * 
     * @return [type]
     */
    public function setResponseCode(int $code)
    {
        return http_response_code($code);
    }
}