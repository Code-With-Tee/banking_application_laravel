<?php

namespace App\Enums;

enum TransactionSourceEnum
{
    case INTERNAL;
    case EXTERNAL;

    public function source(): string
    {
        return match ($this) {
            self::INTERNAL => 'internal',
            self::EXTERNAL => 'external',
        };
    }
}
