<?php

namespace App\Core;

/**
 * Class describing http response
 */
class Response
{
    private string $body = "";

    private array $headers = [];

    private int $statusCode = 0;

    public function setStatusCode(int $code)
    {
        $this->statusCode = $code;
    }

    public function redirect(string $url)
    {
        header("Location: $url");
    }

    public function addHeader(string $header)
    {
        $this->headers[] = $header;
    }

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
        if ($this->statusCode)
        {
            http_response_code($this->statusCode);
        }
        
        foreach ($this->headers as $header)
        {
            header($header);
        }

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
