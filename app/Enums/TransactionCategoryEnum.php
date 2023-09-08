<?php

namespace App\Enums;

enum TransactionCategoryEnum: string
{
    case WITHDRAW = 'withdrawal';
    case DEPOSIT = 'deposit';
}
