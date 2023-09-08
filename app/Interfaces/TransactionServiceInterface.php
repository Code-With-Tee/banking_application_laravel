<?php

namespace App\Interfaces;

use App\Dto\TransactionDto;
use App\Enums\TransactionTypeEnum;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface TransactionServiceInterface
{

    public function modelQuery(): Builder;

    public function createTransaction(TransactionDto $dto): void;

    public function getTransactions(
        int|string  $userIdOrAccountNumber,
        int|null    $perPage = 15,
        bool        $paginate = true,
        string|null $category = null,
        string|null $start_date = null,
        string|null $end_date = null
    ): Collection|LengthAwarePaginator;

    public function getTransactionsByUserId(int $userId, Builder $builder): Builder;

    public function getTransactionsByAccountNumber(string $accountNumber, Builder $builder): Builder;

    public function getTransactionById(int $transactionId): Transaction;

    public function getTransaction(int|string $transactionIdOrReference): Transaction;

    public function getTransactionByReference(string $reference): Transaction;

    public function downloadTransactionHistory(Account $account, Carbon $fromDate, Carbon $toDate): Collection;

    public function generateReference(): string;
}
