<?php

namespace App\Services;

use App\Dto\AccountDto;
use App\Dto\DepositDto;
use App\Dto\TransactionDto;
use App\Dto\TransferDto;
use App\Dto\UserDto;
use App\Dto\WithdrawalDto;
use App\Enums\TransactionSourceEnum;
use App\Enums\TransactionCategoryEnum;
use App\Events\DepositEvent;
use App\Events\TransferEvent;
use App\Events\WithdrawalEvent;
use App\Exceptions\AccountBlockedException;
use App\Exceptions\AmountToLowException;
use App\Exceptions\ANotFoundException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountNumber;
use App\Exceptions\InvalidPinSuppliedException;
use App\Exceptions\SameAccountException;
use App\Interfaces\AccountServiceInterface;
use App\Models\Account;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountService implements AccountServiceInterface
{


    public function __construct(
        private readonly TransactionService $transactionService,
        private readonly UserService        $userService,
        private readonly TransferService    $transferService,
    )
    {

    }

    public function modelQuery(): Builder
    {
        return Account::query();
    }

    public function createAccount(UserDto $userDto): Account
    {
        /** @var Account $account */
        $account = $this->modelQuery()->create([
            'account_number' => substr($userDto->getPhoneNumber(), -10),
            'user_id' => $userDto->getId(),
        ]);
        return $account;
    }

    /**
     * @throws ANotFoundException
     */
    public function getAccount(int|string $accountIdOrNumber): Account
    {

        $account = null;

        if (is_int($accountIdOrNumber)) {
            $account = $this->getAccountById($accountIdOrNumber);
        }

        if (is_int($accountIdOrNumber) && !$account) {
            $account = $this->getAccountByUserId($accountIdOrNumber);
        }

        if (is_string($accountIdOrNumber) && !$account) {
            $account = $this->getAccountByAccountNumber($accountIdOrNumber);
        }

        if (!$account) {
            throw new ANotFoundException("Account could not be found");
        }
        /** @var Account $account */
        return $account;
    }


    public function getAccountByAccountNumber(string $accountNumber): Builder|Model|null
    {
        return $this->modelQuery()->where('account_number', $accountNumber)->first();
    }


    public function getAccountByUserId(int $userId): Builder|Model|null
    {
        /** @var Account $account */
        $account = $this->modelQuery()->where('user_id', $userId)->first();
        if ($account) {
            if ($account->user_id !== $userId) {
                $account = null;
            }
        }
        return $account;
    }

    public function getAccountById(int $id): Builder|Model|null
    {
        return $this->modelQuery()->where('id', $id)->first();
    }

    /**
     * @param DepositDto $depositDto
     * @param bool $confirmed
     * @return TransactionDto
     * @throws AmountToLowException
     * @throws InvalidAccountNumber
     */
    public function deposit(DepositDto $depositDto, bool $confirmed = true): TransactionDto
    {

        if ($depositDto->getAmount() <= 0) {
            throw new AmountToLowException();
        }
        try {
            DB::beginTransaction();
            $accountQuery = $this->modelQuery()->where('account_number', $depositDto->getAccountNumber());
            $this->accountExists($accountQuery);
            /** @var Account $lockedAccount */
            $lockedAccount = $accountQuery->lockForUpdate()->first();
            $accountDto = AccountDto::fromModel($lockedAccount);
            $transactionDto = $this->getTransactionDto($accountDto,
                $depositDto->getAmount(),
                $depositDto->getCategory(),
                $confirmed,
                $depositDto->getDescription()
            );
            event(new DepositEvent($transactionDto, $accountDto, $lockedAccount));
            DB::commit();
            return $transactionDto;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }

    /**
     * @throws AccountBlockedException
     * @throws InsufficientBalanceException
     * @throws Exception
     */
    public function withdraw(WithdrawalDto $withdrawalDto, ?array $meta = null, bool $confirmed = true): TransactionDto
    {

        try {
            DB::beginTransaction();
            $accountQuery = $this->modelQuery()->where('account_number', $withdrawalDto->getAccountNumber());
            $this->accountExists($accountQuery);

            /** @var Account $lockedAccount */
            $lockedAccount = $accountQuery->lockForUpdate()->first();

            if (!$this->userService->pinIsValid($lockedAccount->user_id, $withdrawalDto->getPin())) {
                throw new InvalidPinSuppliedException();
            }

            $accountDto = AccountDto::fromModel($lockedAccount);
            $this->canWithdraw($accountDto, $withdrawalDto->getAmount());
            $transactionDto = $this->getTransactionDto($accountDto, $withdrawalDto->getAmount(), $withdrawalDto->getCategory(), $confirmed, $withdrawalDto->getDescription());
            event(new WithdrawalEvent($transactionDto, $accountDto, $lockedAccount));
            DB::commit();
            return $transactionDto;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }


    }


    /**
     * @param string $senderAccountNumber
     * @param string $receiverAccountNumber
     * @param string $pin
     * @param int|float $amount
     * @param array|null $meta
     * @return TransferDto
     * @throws ANotFoundException
     * @throws AccountBlockedException
     * @throws InsufficientBalanceException
     * @throws InvalidAccountNumber
     * @throws InvalidPinSuppliedException|SameAccountException
     */
    public function transfer(string    $senderAccountNumber, string $receiverAccountNumber,
                             string    $pin,
                             int|float $amount, string|null $description = null ,?array $meta = null): TransferDto
    {

        try {
            DB::beginTransaction();
            if ($senderAccountNumber == $receiverAccountNumber) {
                throw new  SameAccountException();
            }
            $senderAccountQuery = $this->modelQuery()->where('account_number', $senderAccountNumber);
            $receiverAccountQuery = $this->modelQuery()->where('account_number', $receiverAccountNumber);

            $this->accountExists($senderAccountQuery);
            $this->accountExists($receiverAccountQuery);

            /** @var Account $lockedSenderAccount */
            $lockedSenderAccount = $senderAccountQuery->lockForUpdate()->first();
            /** @var Account $lockedReceiverAccount */
            $lockedReceiverAccount = $receiverAccountQuery->lockForUpdate()->first();

            if (!$this->userService->pinIsValid($lockedSenderAccount->user_id, $pin)) {
                throw new InvalidPinSuppliedException();
            }
            $lockedSenderAccountDto = AccountDto::fromModel($lockedSenderAccount);
            $lockedReceiverAccountDto = AccountDto::fromModel($lockedReceiverAccount);

            $this->canWithdraw($lockedSenderAccountDto, $amount);

            $senderTransactionDto = $this->getTransactionDto($lockedSenderAccountDto, $amount, TransactionCategoryEnum::WITHDRAW->value, true, $description);
            $receiverTransactionDto = $this->getTransactionDto($lockedReceiverAccountDto, $amount,
                TransactionCategoryEnum::DEPOSIT->value, true, 'j'
            );
            $transferDto = $this->getTransferDto($lockedSenderAccountDto, $lockedReceiverAccountDto, $amount);

            event(new WithdrawalEvent($senderTransactionDto, $lockedSenderAccountDto, $lockedSenderAccount));
            event(new DepositEvent($receiverTransactionDto, $lockedReceiverAccountDto, $lockedReceiverAccount));
            event(new TransferEvent($transferDto, $senderTransactionDto, $receiverTransactionDto, $lockedSenderAccount, $lockedReceiverAccount));
            DB::commit();
            return $transferDto;
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            throw $exception;
        }
    }

    /**
     * @throws AccountBlockedException
     * @throws InsufficientBalanceException
     */
    public function canWithdraw(AccountDto $accountDto, $amount, bool $allowZero = false): bool
    {
        if ($accountDto->isBlocked()) {
            throw new AccountBlockedException();
        }
        if ($accountDto->getBalance() < $amount) {
            throw new InsufficientBalanceException();
        }
        if (!$allowZero && ($accountDto->getBalance() - $amount <= 0)) {
            throw new InsufficientBalanceException();
        }
        return true;
    }

    public function getBalance(Account $account): float
    {
        return $account->balance;
    }

    public function getBalanceInt(Account $account): int
    {
        return (int)$this->getBalance($account);
    }

    /**
     * @throws InvalidAccountNumber
     */
    public function accountExists(Builder $builder): bool
    {
        if (!$builder->exists()) {
            throw new InvalidAccountNumber();
        }
        return true;
    }

    /**
     * @param AccountDto $accountDto
     * @param float|int $amount
     * @param string $category
     * @param bool $confirmed
     * @return TransactionDto
     */
    public function getTransactionDto(AccountDto $accountDto, float|int $amount, string $category, bool $confirmed, $description): TransactionDto
    {
        return new TransactionDto(
            $this->transactionService->generateReference(),
            $accountDto->getUserId(),
            $accountDto->getId(),
            $amount,
            $category,
            $confirmed,
            $description
        );
    }

    /**
     * @param AccountDto $lockedSenderAccountDto
     * @param AccountDto $lockedReceiverAccountDto
     * @param float|int $amount
     * @return TransferDto
     */
    public function getTransferDto(AccountDto $lockedSenderAccountDto, AccountDto $lockedReceiverAccountDto, float|int $amount): TransferDto
    {
        return new TransferDto(
            $this->transferService->generateReference(),
            $lockedSenderAccountDto->getUserId(),
            $lockedSenderAccountDto->getId(),
            $lockedReceiverAccountDto->getUserId(),
            $lockedReceiverAccountDto->getId(),
            $amount
        );
    }


}
