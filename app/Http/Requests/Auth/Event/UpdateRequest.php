<?php

namespace App\Http\Requests\Auth\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            // 'name' => 'required|min:3|max:255|string| new CustomRule',
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'description' => ['required', 'min:3', 'max:30000', 'string'],
            'category' => ['required', 'integer'],
            'name' => ['required', 'min:3', 'max:255', 'string'],
            'price' => ['required', 'integer'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'max_attendees' => ['required', 'integer']
        ];
    }
}
