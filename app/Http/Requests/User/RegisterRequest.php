<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|alpha|min:6|max:32|unique:users,name',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|max:32',
        ];
    }
}