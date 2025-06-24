<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterForm extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required'],
            'repeat_password' => ['required', 'same:password'],
            'sex' => ['required'],
            'weight' => ['required', 'numeric'],
            'height' => ['required', 'numeric'],
//            'age' => ['required', 'integer'],
            'birth_date' => ['required', 'date'],
            'activity_level' => ['required'],
            'body_fat' => ['sometimes'],
            'role' => ['sometimes'],
        ];
    }
}
