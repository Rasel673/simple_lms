<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'video_url' => ['nullable', 'url', 'max:500'],
            'duration' => ['nullable', 'string', 'max:50'],
            'order' => ['nullable', 'integer', 'min:1'],
            'is_preview' => ['boolean'],
            'is_published' => ['boolean'],
            'description' => ['nullable', 'string'],
        ];
    }
}
