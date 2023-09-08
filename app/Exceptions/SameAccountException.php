<?php

namespace App\Exceptions;

class SameAccountException extends \Exception
{
    public function __construct()
    {
        parent::__construct("Sender and receiver account numbers cannot be the same.");
    }
}
