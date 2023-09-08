<?php

namespace App\Services;

use App\Dto\AccountDto;
use App\Dto\TransferDto;
use App\Interfaces\TransferServiceInterface;
use App\Models\Account;
use App\Models\Transfer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class TransferService implements TransferServiceInterface
{
    public function modelQuery(): Builder
    {
        return Transfer::query();
    }

    public function createTransfer(TransferDto $dto): TransferDto
    {
        $data = $dto->make()->removeKeys(['id', 'created_at', 'updated_at'])->toArray();
        Transfer::query()->create($data);
        return $dto;
    }



    public function getTransfersBetweenAccount(AccountDto $firstAccountDto, AccountDto $secondAccountDto): array
    {
        if ($firstAccountDto->getAccountNumber() == $secondAccountDto->getAccountNumber()) {
            throw new BadRequestException("Same account provided");
        }
        return $this->modelQuery()
            ->whereIn('sender_id', [$firstAccountDto->getUserId(), $secondAccountDto->getUserId()])
            ->orWhereIn('recipient_id', [$firstAccountDto->getUserId(), $secondAccountDto->getUserId()])
            ->get();
    }

    public function getTransferById(int $transferId): ?Transfer
    {
        return $this->getTransfer($transferId);
    }

    public function getTransferByReference(string $reference): ?Transfer
    {
       return $this->getTransfer($reference);
    }



    public function generateReference(): string
    {
        return Str::upper( "TF" . "/" . Carbon::now()->getTimestampMs() . "/" . Str::random(4));
    }

    public function getTransfer(int|string $transferIdOrReference): Transfer
    {
        /** @var Transfer $transfer */
        $transfer = null;
        if (is_int($transferIdOrReference)) {
            $transfer = $this->modelQuery()->where('id', $transferIdOrReference)->first();
        }
        if (is_string($transferIdOrReference)) {
            $transfer = $this->modelQuery()->where('reference', $transferIdOrReference)->first();
        }
        if (!$transfer) {
            throw new ModelNotFoundException("Transaction could not be found");
        }
        return $transfer;
    }
}
