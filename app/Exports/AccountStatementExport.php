<?php

namespace App\Exports;

use App\Enums\TransactionCategoryEnum;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProperties;

class AccountStatementExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(public readonly Collection $transactions)
    {
    }

    /**
    * @return Collection
    */
    public function collection(): Collection
    {
        return $this->transactions;
    }

    public function headings(): array
    {
        return[
            "Year",
            'Date',
            'Time',
            'Money In',
            'Money Out',
            'Category',
            'Description',
            'Balance'
        ];
    }


    public function map($row): array
    {
        return [
            Carbon::parse($row->date)->format('Y'),
            Carbon::parse($row->date)->format('F jS Y'),
            Carbon::parse($row->date)->format('H:m:s'),
            $this->amount($row->category, $row->amount, 'money_in'),
            $this->amount($row->category, $row->amount, 'money_out'),
            $row->category,
            $row->description,
            $row->balance
        ];
    }

    private function amount($category, $amount, $column): int|float
    {

        if ($category == TransactionCategoryEnum::DEPOSIT->value && $column == 'money_in'){
            return $amount;
        }
        if ($category == TransactionCategoryEnum::WITHDRAW->value && $column == 'money_out'){
            return $amount;
        }
        return 0;
    }
}
