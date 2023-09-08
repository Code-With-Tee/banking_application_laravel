<?php

namespace App\Exceptions;
use  Exception;
use Throwable;

class AccountBlockedException extends  Exception
{
    public function __construct()
    {
        parent::__construct( "Your Account has been blocked, please contact admin");
    }
}
