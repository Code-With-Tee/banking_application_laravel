<?php

namespace App\Exceptions;

use Throwable;

class InsufficientBalanceException extends \Exception
{
    public function __construct()
    {
        parent::__construct( "Insufficient Account Balance");
    }
}
