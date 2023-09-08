<?php

namespace App\Services;

use App\Dto\TransactionDto;
use App\Exceptions\ANotFoundException;
use App\Interfaces\TransactionServiceInterface;
use App\Models\Account;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;

class TransactionService implements TransactionServiceInterface
{
    public function modelQuery(): Builder
    {
        return Transaction::query();
    }


    public function createTransaction(TransactionDto $dto): void
    {

        $data = $dto->make()->removeKeys(['id', 'created_at', 'updated_at'])->toArray();
        Transaction::query()->create($data);
    }

    public function updateTransactionsTransferId(array $references, int $transferId): void
    {
        $this->modelQuery()->whereIn('reference', $references)->update([
            'transfer_id' => $transferId
        ]);
    }

    public function updateTransactionBalance(string $reference, int $balance): void
    {
        $this->modelQuery()->where('reference', $reference)->update([
            'balance' => $balance
        ]);
    }

    public function getTransactions(
        int|string  $userIdOrAccountNumber,
        int|null    $perPage = 15,
        bool        $paginate = true,
        string|null $category = null,
        string|null $start_date = null,
        string|null $end_date = null
    ): Collection|LengthAwarePaginator
    {
        if (!$paginate) {
            $start_date = $start_date != null ? Carbon::parse($start_date)->toDateString() : Carbon::now()->startOfMonth()->toDateString();
            $end_date = $end_date != null ? Carbon::parse($end_date)->toDateString() : Carbon::now()->endOfMonth()->toDateString();
        }


        $query = $this->modelQuery()
            ->when($category, fn($query) => $query->where('category', $category))
            ->when($start_date && $end_date, fn($query) => $query->whereBetween('date', [$start_date, $end_date]))
            ->where('confirmed', true)
            ->latest('date');
        if (is_int($userIdOrAccountNumber)) {
            $query = $this->getTransactionsByUserId($userIdOrAccountNumber, $query);
        }
        if (is_string($userIdOrAccountNumber)) {
            $query = $this->getTransactionsByAccountNumber($userIdOrAccountNumber, $query);
        }
        if ($paginate) {
            return $query->paginate($perPage);
        }
        return $query->get();
    }


    public function getTransactionsByUserId($userId, Builder $builder): Builder
    {
        return $builder->where('user_id', $userId);
    }

    public function getTransactionsByAccountNumber(string $accountNumber, Builder $builder): Builder
    {
        return $builder->where('accountNumber', $accountNumber);
    }

    /**
     * @throws ANotFoundException
     */
    public function getTransaction(int|string $transactionIdOrReference): Transaction
    {
        /** @var Transaction $transaction */
        $transaction = null;
        if (is_int($transactionIdOrReference)) {
            $transaction = $this->modelQuery()->where('id', $transactionIdOrReference)->first();
        }
        if (is_string($transactionIdOrReference)) {
            $transaction = $this->modelQuery()->where('reference', $transactionIdOrReference)->first();
        }
        if (!$transaction) {
            throw new ANotFoundException("Transaction could not be found");
        }
        return $transaction;
    }

    /**
     * @throws ANotFoundException
     */
    public function getTransactionById(int $transactionId): Transaction
    {
        return $this->getTransaction($transactionId);
    }

    /**
     * @throws ANotFoundException
     */
    public function getTransactionByReference(string $reference): Transaction
    {
        return $this->getTransaction($reference);
    }

    public function downloadTransactionHistory(Account $account, Carbon $fromDate, Carbon $toDate): Collection
    {
        return $account->transactions()->whereBetween('date', [$fromDate->format('yyyy-mm-dd'), $toDate->format('yyyy-mm-dd')])->get();
    }

    public function generateReference(): string
    {
        return Str::upper("TR" . "/" . Carbon::now()->getTimestampMs() . "/" . Str::random(4));
    }
}
