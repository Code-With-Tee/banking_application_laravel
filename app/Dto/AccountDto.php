<?php

namespace App\Dto;

use App\Interfaces\DtoInterface;
use App\Models\Account;
use App\Traits\DataManipulatorTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class AccountDto
{
    use DataManipulatorTrait;
    private int $id;
    private int $user_id;
    private string $account_number;
    private bool $blocked;

    private float $balance;
    private Carbon $created_at;

    private Carbon $updated_at;
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AccountDto
     */
    public function setId(int $id): AccountDto
    {
        $this->id = $id;
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
     * @param int $user_id
     * @return AccountDto
     */
    public function setUserId(int $user_id): AccountDto
    {
        $this->user_id = $user_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->account_number;
    }

    /**
     * @param string $account_number
     * @return AccountDto
     */
    public function setAccountNumber(string $account_number): AccountDto
    {
        $this->account_number = $account_number;
        return $this;
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return $this->blocked;
    }

    /**
     * @param bool $blocked
     * @return AccountDto
     */
    public function setBlocked(bool $blocked): AccountDto
    {
        $this->blocked = $blocked;
        return $this;
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @param float $balance
     * @return AccountDto
     */
    public function setBalance(float $balance): AccountDto
    {
        $this->balance = $balance;
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
     * @return AccountDto
     */
    public function setCreatedAt(Carbon $created_at): AccountDto
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
     * @return AccountDto
     */
    public function setUpdatedAt(Carbon $updated_at): AccountDto
    {
        $this->updated_at = $updated_at;
        return $this;
    }


    public static function fromModel(Model|Account $model): self
    {
        $dto = new self();

        $dto->setUserId($model->user_id)
            ->setId($model->id)
            ->setAccountNumber($model->account_number)
            ->setBalance($model->balance)
            ->setBlocked($model->blocked);
        return  $dto;
    }
}
