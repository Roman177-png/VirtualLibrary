<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|between:1,5',
            'content' => 'required|string|min:10',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Please provide your name.',
            'rating.required' => 'Please provide a rating between 1 and 5.',
            'content.required' => 'The review content cannot be empty.',
            'content.min' => 'The review content should be at least 10 characters.',
            'rating.between' => 'Rating should be between 1 and 5.',
        ];
    }
}
