<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class TransferRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'receiver_account_number' => ['required', 'integer'],
            'amount' => ['required', 'integer', 'min:10'],
            'pin' => ['required', 'string'],
            'description'=> ['nullable', 'string', 'max:200']
        ];
    }
}
