<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RechargeRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        // merge current user’s id into the incoming data
        $this->merge([
            'user_id' => $this->user()->id,
        ]);
    }
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'integer'],
            'currency_id' => ['required', 'integer'],
            'method' => ['required', 'string'],
            'amount' => ['required', 'integer', 'min:1', 'max:10000'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
