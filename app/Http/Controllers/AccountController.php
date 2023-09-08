<?php

namespace App\Http\Controllers;

use App\Dto\DepositDto;
use App\Dto\WithdrawalDto;
use App\Exceptions\AccountBlockedException;
use App\Exceptions\AmountToLowException;
use App\Exceptions\ANotFoundException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\InvalidAccountNumber;
use App\Exceptions\InvalidPinSuppliedException;
use App\Exceptions\SameAccountException;
use App\Http\Requests\DepositRequest;
use App\Http\Requests\TransferRequest;
use App\Http\Requests\WithdrawRequest;
use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    public function __construct(private readonly AccountService $accountService)
    {
    }

    /**
     * @throws AmountToLowException
     * @throws InvalidAccountNumber
     */
    public function deposit(DepositRequest $request): JsonResponse
    {
        $depositDto = new DepositDto();
        $depositDto->setAccountNumber($request->input('account_number'));
        $depositDto->setAmount($request->input('amount'));
        $depositDto->setDescription($request->input('description'));
        $depositDto->setCategory();
        $transactionDto = $this->accountService->deposit($depositDto);

        return $this->respondSuccess(['transaction' => $transactionDto->properties], 'Deposit Request submitted');
    }

    /**
     * @throws AccountBlockedException
     * @throws InvalidAccountNumber
     * @throws InsufficientBalanceException
     * @throws ANotFoundException
     * @throws InvalidPinSuppliedException
     * @throws SameAccountException
     */
    public function transfer(TransferRequest $request): JsonResponse
    {
        $user = $request->user();
        $senderAccount = $this->accountService->getAccount($user->id);
        $transactionDto = $this->accountService->transfer($senderAccount->account_number,
            $request->input('receiver_account_number'),
            $request->input('pin'),
            $request->input('amount'),
            $request->input('description')
        );
        return $this->respondSuccess(['transaction' => $transactionDto->properties], 'Deposit Request submitted');
    }

    /**
     * @throws AccountBlockedException
     * @throws InsufficientBalanceException
     * @throws ANotFoundException
     */
    public function withdraw(WithdrawRequest $request): JsonResponse
    {
        $user = $request->user();
        $account = $this->accountService->getAccount($user->id);
        $withdrawalDto = new WithdrawalDto();
        $withdrawalDto->setAccountNumber($account->account_number);
        $withdrawalDto->setAmount($request->input('amount'));
        $withdrawalDto->setDescription($request->input('description'));
        $withdrawalDto->setPin($request->input('pin'));
        $withdrawalDto->setCategory();
        $transactionDto = $this->accountService->withdraw($withdrawalDto);
        return $this->respondSuccess(['transaction' => $transactionDto->properties], 'Withdrawal Request submitted');
    }


    /**
     * Display the specified resource.
     * @throws ANotFoundException
     */
    public function balance($account): JsonResponse
    {
        $account = $this->accountService->getAccount($account);
        return $this->respondSuccess(['account' => $account]);
    }

}
