<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
{

    public function prepareForValidation(): void
    {
        // merge current user’s id into the incoming data
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'to' => ['required', 'email', 'exists:users,email'],
            'amount' => ['required', 'integer'],
            'due_date' => ['nullable', 'date'],
            'details' => ['nullable'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
