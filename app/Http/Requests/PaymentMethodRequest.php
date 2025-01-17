<?php

namespace App\Http\Requests;

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
                'number' => ['required', 'string', 'size:16', 'regex:/^[0-9]+$/'],
                'expiration_date' => ['required', 'date', 'after:today'],
                'cvv' => ['required', 'string', 'size:3', 'regex:/^[0-9]+$/'],
            ]);
        } elseif ($this->input('type') === 'paypal') {
            $rules = array_merge($rules, [
                'email' => ['required', 'email'],
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
            'number.regex' => 'The card number must contain only digits.',
            'cvv.regex' => 'The CVV must contain only digits.',
            'expiration_date.after' => 'The card expiration date must be in the future.',
        ];
    }
}

