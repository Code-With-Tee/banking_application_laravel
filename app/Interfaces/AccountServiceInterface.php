<?php

namespace App\Interfaces;

use App\Dto\AccountDto;
use App\Dto\DepositDto;
use App\Dto\TransactionDto;
use App\Dto\TransferDto;
use App\Dto\UserDto;
use App\Dto\WithdrawalDto;
use App\Enums\TransactionSourceEnum;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;

interface AccountServiceInterface
{
    public function modelQuery(): Builder;

    public function createAccount(UserDto $userDto): Account;


    /** @throws ModelNotFoundException */
    public function getAccount(string|int $accountIdOrNumber): Account;


    public function deposit(DepositDto $depositDto ,bool $confirmed = true): TransactionDto;

    public function withdraw(WithdrawalDto $withdrawalDto, ?array $meta = null, bool $confirmed = true): TransactionDto;

    public function transfer(string $senderAccountNumber, string $receiverAccountNumber, string $pin, int|float $amount, string|null $description , ?array $meta = null): TransferDto;

    public function canWithdraw(AccountDto $accountDto, int|float $amount, bool $allowZero = false): bool;

    public function getBalance(Account $account): float;

    public function getBalanceInt(Account $account): int;


}
