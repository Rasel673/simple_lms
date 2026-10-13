<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'question_text' => ['required', 'string'],
            'explanation' => ['nullable', 'string'],
            'marks' => ['required', 'integer', 'min:1'],
            'order' => ['nullable', 'integer', 'min:1'],
            'options' => ['required', 'array', 'min:2'],
            'options.*' => ['required', 'string', 'max:500'],
            'correct_option' => ['required', 'integer', 'min:0'],
        ];
    }
}
