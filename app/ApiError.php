<?php
namespace App\Exceptions;

use Exception;

class ApiError extends Exception
{
    protected $statusCode;

    public function __construct($message = "", $code = 500, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $code;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }
}