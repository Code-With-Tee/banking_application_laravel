<?php

namespace App\Exceptions;

use Throwable;

class AmountToLowException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Amount must be greater than 0");
    }
}
