<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return  [
            'name' => ['required', 'string', 'max:255'],
            'email' =>['required'  , 'email' , 'unique:users'  , 'max:255'],
            'password_confirmation' => ['required', 'string', 'same:password'],
            'password' => ['required', 'string', 'min:8', 'regex:/[a-z]/', 'regex:/[A-Z]/', 'regex:/[0-9]/', 'regex:/[@$!%*?&#]/'],
            'mobile_country_code' => ['required', 'string', 'max:5'],
            'mobile' => ['required', 'string', 'max:20'],
        ];

    }
}
