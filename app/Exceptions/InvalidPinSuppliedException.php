<?php

namespace App\Exceptions;

class InvalidPinSuppliedException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Invalid pin supplied");
    }
}
