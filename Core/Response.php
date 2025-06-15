<?php

namespace App\Core;

/**
 * Class describing http response
 */
class Response
{

    private string $body = "";

    public function setBody(string $body)
    {
        $this->body = $body;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function send()
    {
        echo $this->body;
    }

    /**
     * Get current response http code
     * @return int
     */
    public function getResponseCode()
    {
        return http_response_code();
    }
    /**
     * Sets http response code of the current response
     * @param int $code
     * 
     * @return int
     */
    public function setResponseCode(int $code)
    {
        return http_response_code($code);
    }
}
