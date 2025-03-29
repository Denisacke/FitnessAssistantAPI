<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ExerciseForm extends FormRequest
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
            'body_part' => 'required',
            'name' => 'required',
            'gif_url' => 'required',
            'muscle_target' => 'required',
            'instructions' => 'required',
            'role' => 'sometimes',
        ];
    }
}
