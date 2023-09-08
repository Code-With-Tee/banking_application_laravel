<?php

namespace App\Http\Requests;

use App\Enums\TransactionTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class FilterTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'start_date' => [Rule::requiredIf(request()->query('end_date') != null || request()->query('paginate') == false), 'date_format:Y-m-d'],
            'end_date' => [Rule::requiredIf(request()->query('start_date') != null || request()->query('paginate') == false), 'date_format:Y-m-d', 'after_or_equal:start_date'],
            'type' => ['nullable', 'string', new Enum(TransactionTypeEnum::class)],
            'per_page' => ['nullable', 'integer', 'min:1'],
            'paginate' => ['nullable', 'boolean']
        ];
    }
}
