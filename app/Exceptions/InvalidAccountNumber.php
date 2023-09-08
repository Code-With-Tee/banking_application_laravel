<?php

namespace App\Exceptions;

use Throwable;

class InvalidAccountNumber extends \Exception
{
    public function __construct()
    {
        parent::__construct("Invalid Account Number");
    }
}
