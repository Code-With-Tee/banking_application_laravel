<?php

namespace App\Http\Controllers;

use App\Enums\TransactionTypeEnum;
use App\Exports\AccountStatementExport;
use App\Http\Requests\FilterTransactionRequest;
use App\Models\Transaction;
use App\Services\TransactionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class TransactionController extends Controller
{
    public function __construct(private readonly TransactionService $transactionService)
    {
    }


    public function index(FilterTransactionRequest $request): JsonResponse
    {
        $transactions = $this->transactionService->getTransactions(
            $request->user()->id,
            $request->query('per_page', 15),
            $request->query('paginate', true),
            $request->query('category'),
            $request->query('start_date'),
            $request->query('end_date')
        );
        return $this->respondSuccess(['transactions' => $transactions], 'Transactions retrieved');
    }

    public function download(FilterTransactionRequest $request): BinaryFileResponse
    {
        $transactions = $this->transactionService->getTransactions(
            1,
            null,
            false,
            $request->query('category'),
            $request->query('start_date'),
            $request->query('end_date')
        );
       return Excel::download( new AccountStatementExport($transactions), 'Account_Statement.csv');
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

}
