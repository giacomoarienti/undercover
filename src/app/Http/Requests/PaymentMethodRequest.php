<?php

namespace App\Http\Requests;

use App\Rules\ValidCardExpirationDate;
use Illuminate\Foundation\Http\FormRequest;

class PaymentMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorization is handled through middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'type' => ['required', 'string', 'in:card,paypal'],
        ];

        // Add type-specific validation rules
        if ($this->input('type') === 'card') {
            $rules = array_merge($rules, [
                'card_number' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
                'card_expiration_date' => ['required', 'string', 'size:7', new ValidCardExpirationDate()],
                'card_cvv' => ['required', 'string', 'regex:/^[0-9]{3,4}$/'],
            ]);
        } elseif ($this->input('type') === 'paypal') {
            $rules = array_merge($rules, [
                'paypal_email' => ['required', 'email'],
            ]);
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'card_number.regex' => 'The card number must contain only digits.',
            'card_cvv.regex' => 'The CVV must contain only digits.',
            'card_expiration_date.regex' => 'The expiration date must be in the format MM/YYYY.',
        ];
    }
}

