<?php

namespace App\Dto;

use App\Enums\TransactionCategoryEnum;

class DepositDto
{
    private string $account_number;

    private  int|float $amount;

    private   string|null $description;

    private string $category;

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }


    public function setCategory(): void
    {
        $this->category = TransactionCategoryEnum::DEPOSIT->value;
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
     */
    public function setAccountNumber(string $account_number): void
    {
        $this->account_number = $account_number;
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
     */
    public function setAmount(float $amount): void
    {
        $this->amount = $amount;
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

}
