<?php

namespace App\Interfaces;

use App\Dto\AccountDto;
use App\Dto\TransferDto;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Database\Eloquent\Builder;

interface TransferServiceInterface
{
    public function modelQuery(): Builder;
    public function createTransfer(TransferDto $dto): TransferDto;

    public function getTransfersBetweenAccount(AccountDto $firstAccountDto, AccountDto $secondAccountDto): array;

    public function getTransferById(int $transferId): ?Transfer;

    public function getTransfer(int|string $transferIdOrReference): Transfer;

    public function getTransferByReference(string $reference): ?Transfer;

    public function generateReference(): string;
}
