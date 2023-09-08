<?php

namespace App\Listeners;

use App\Events\TransferEvent;
use App\Services\AccountService;
use App\Services\TransactionService;
use App\Services\TransferService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class TransferListener
{
    /**
     * Create the event listener.
     * @param TransferService $transferService
     * @param TransactionService $transactionService
     */
    public function __construct(private readonly TransferService $transferService, private readonly TransactionService $transactionService)
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TransferEvent $event): void
    {
        $this->transferService->createTransfer($event->transferDto);
        $transfer = $this->transferService->getTransfer($event->transferDto->getReference());
        $this->transactionService->updateTransactionsTransferId(
            [$event->senderTransactionDto->getReference(), $event->receiverTransactionDto->getReference()],
            $transfer->id
        );
        $this->transactionService->updateTransactionBalance($event->senderTransactionDto->getReference(), $event->senderAccount->balance);
        $this->transactionService->updateTransactionBalance($event->receiverTransactionDto->getReference(), $event->receiverAccount->balance);
    }
}
