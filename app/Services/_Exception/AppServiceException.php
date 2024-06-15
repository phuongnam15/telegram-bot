<?php

namespace App\Services\_Exception;

use Throwable;

class AppServiceException extends \Exception
{
    public function __construct($message = "", $code = 500, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
