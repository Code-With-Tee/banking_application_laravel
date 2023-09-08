<?php

namespace App\Dto;

use App\Traits\DataManipulatorTrait;
use Carbon\Carbon;

class TransferDto
{
    use DataManipulatorTrait;
    private int $id;
    private int $sender_id;
    private int $sender_account_id;

    private int $recipient_id;

    private int $recipient_account_id;

    private float $amount;

    private string $status;

    private string $reference;

    private Carbon $created_at;

    private Carbon $updated_at;

    /**
     * @param int $sender_id
     * @param int $sender_account_id
     * @param int $recipient_id
     * @param int $recipient_account_id
     * @param float $amount
     */
    public function __construct(string $reference, int $sender_id, int $sender_account_id, int $recipient_id, int $recipient_account_id, float $amount)
    {
        $this->reference = $reference;
        $this->sender_id = $sender_id;
        $this->sender_account_id = $sender_account_id;
        $this->recipient_id = $recipient_id;
        $this->recipient_account_id = $recipient_account_id;
        $this->amount = $amount;
        $this->status = 'successes';
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getReference(): string
    {
        return $this->reference;
    }

    /**
     * @param string $reference
     */
    public function setReference(string $reference): void
    {
        $this->reference = $reference;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return TransferDto
     */
    public function setId(int $id): TransferDto
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSenderId(): int
    {
        return $this->sender_id;
    }

    /**
     * @param int $senderId
     * @return TransferDto
     */
    public function setSenderId(int $senderId): TransferDto
    {
        $this->sender_id = $senderId;
        return $this;
    }

    /**
     * @return int
     */
    public function getSenderAccountId(): int
    {
        return $this->sender_account_id;
    }

    /**
     * @param int $senderAccountId
     * @return TransferDto
     */
    public function setSenderAccountId(int $senderAccountId): TransferDto
    {
        $this->sender_account_id = $senderAccountId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecipientId(): int
    {
        return $this->recipient_id;
    }

    /**
     * @param int $recipientId
     * @return TransferDto
     */
    public function setRecipientId(int $recipientId): TransferDto
    {
        $this->recipient_id = $recipientId;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecipientAccountId(): int
    {
        return $this->recipient_account_id;
    }

    /**
     * @param int $recipientAccountId
     * @return TransferDto
     */
    public function setRecipientAccountId(int $recipientAccountId): TransferDto
    {
        $this->recipient_account_id = $recipientAccountId;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * @param float $amount
     * @return TransferDto
     */
    public function setAmount(float $amount): TransferDto
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->created_at;
    }

    /**
     * @param Carbon $createdAt
     * @return TransferDto
     */
    public function setCreatedAt(Carbon $createdAt): TransferDto
    {
        $this->created_at = $createdAt;
        return $this;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updated_at;
    }

    /**
     * @param Carbon $updatedAt
     * @return TransferDto
     */
    public function setUpdatedAt(Carbon $updatedAt): TransferDto
    {
        $this->updated_at = $updatedAt;
        return $this;
    }


}
