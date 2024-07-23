<?php

namespace App\Http\Requests;

use App\Http\Requests\_Abstracts\ApiBaseRequest;

class RegisterRequest extends ApiBaseRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|min:6|confirmed',
            'name' => 'required',
        ];
    }
}
