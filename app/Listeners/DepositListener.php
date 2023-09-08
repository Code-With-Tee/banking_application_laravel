<?php

namespace App\Listeners;


use App\Enums\TransactionCategoryEnum;
use App\Events\DepositEvent;
use App\Services\TransactionService;

class DepositListener
{

    /**
     * Create the event listener.
     */
    public function __construct(public readonly TransactionService $transactionService)
    {

    }

    /**
     * Handle the event.
     */
    public function handle(DepositEvent $event): void
    {
        if ($event->transactionDto->getType() != TransactionCategoryEnum::DEPOSIT->value) {
            return;
        }
        $this->transactionService->createTransaction($event->transactionDto);
        if ($event->transactionDto->isConfirmed()) {
            $account = $event->account;
            $account->balance += $event->transactionDto->getAmount();
            $account->save();
            $account = $account->fresh();
            $this->transactionService->updateTransactionBalance($event->transactionDto->getReference(), $account->balance);
        }
    }
}
