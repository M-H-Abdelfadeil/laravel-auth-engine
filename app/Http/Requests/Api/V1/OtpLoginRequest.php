<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\LoginByEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class OtpLoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // public function authorize(): bool
    // {
    //     return false;
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return match (config('auth.login_via', LoginByEnum::EMAIL->value)) {
            LoginByEnum::EMAIL->value => [
                'email' => ['required', 'email'],

            ],
            LoginByEnum::MOBILE->value => [
                'mobile' => ['required', 'string'],
                'mobile_country_code' => ['required', 'string'],
            ],
            default => [
                'email' => ['nullable', 'email', 'required_without:mobile'],
                'mobile' => ['nullable', 'string', 'required_without:email'],
                'mobile_country_code' => [
                    'required_with:mobile',
                    'string',
                ],
            ],
        };
    }
}
