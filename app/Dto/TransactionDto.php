<?php

namespace App\Dto;

use App\Interfaces\DtoInterface;
use App\Traits\DataManipulatorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class TransactionDto implements DtoInterface
{
    use DataManipulatorTrait;
    private int $id;
    private string $reference;

    private int $user_id;

    private int $account_id;
    private int $transfer_id;

    private float $amount;

    private string $category;

    private string $description;

    private Carbon $date;



    private  bool $confirmed;

    private Carbon $created_at;

    private Carbon $updated_at;

    /**
     * @param string $reference
     * @param int $userId
     * @param int $accountId
     * @param float $amount
     * @param string $category
     * @param bool $confirmed
     */
    public function __construct(string $reference, int $userId, int $accountId, float $amount, string $category,  bool $confirmed, string|null $description)
    {

        $this->reference = $reference;
        $this->user_id = $userId;
        $this->account_id = $accountId;
        $this->amount = $amount;
        $this->category = $category;
        $this->confirmed = $confirmed;
        $this->date = Carbon::now();
        $this->description = $description;
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
     * @return TransactionDto
     */
    public function setId(int $id): TransactionDto
    {
        $this->id = $id;
        return $this;
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
     * @return TransactionDto
     */
    public function setReference(string $reference): TransactionDto
    {
        $this->reference = $reference;
        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $userId
     * @return TransactionDto
     */
    public function setUserId(int $userId): TransactionDto
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @return int
     */
    public function getAccountId(): int
    {
        return $this->account_id;
    }

    /**
     * @param int $accountId
     * @return TransactionDto
     */
    public function setAccountId(int $accountId): TransactionDto
    {
        $this->account_id = $accountId;
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
     * @return TransactionDto
     */
    public function setAmount(float $amount): TransactionDto
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     * @return TransactionDto
     */
    public function setType(string $category): TransactionDto
    {
        $this->category = $category;
        return $this;
    }


    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->confirmed;
    }

    /**
     * @param bool $confirmed
     * @return TransactionDto
     */
    public function setConfirmed(bool $confirmed): TransactionDto
    {
        $this->confirmed = $confirmed;
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
     * @param Carbon $created_at
     * @return TransactionDto
     */
    public function setCreatedAt(Carbon $created_at): TransactionDto
    {
        $this->created_at = $created_at;
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
     * @param Carbon $updated_at
     * @return TransactionDto
     */
    public function setUpdatedAt(Carbon $updated_at): TransactionDto
    {
        $this->updated_at = $updated_at;
        return $this;
    }

    /**
     * @return int
     */
    public function getTransferId(): int
    {
        return $this->transfer_id;
    }

    /**
     * @param int $transfer_id
     */
    public function setTransferId(int $transfer_id): void
    {
        $this->transfer_id = $transfer_id;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory(string $category): void
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return Carbon
     */
    public function getDate(): Carbon
    {
        return $this->date;
    }

    /**
     * @param Carbon $date
     */
    public function setDate(Carbon $date): void
    {
        $this->date = $date;
    }





    public static function fromApiFormRequest(FormRequest $request)
    {
        // TODO: Implement fromApiFormRequest() method.
    }



    public static function fromModel(Model $model) : TransactionDto
    {
        $dto = new self();

        // TODO: Implement fromModel() method.
        return $dto;

    }


}
