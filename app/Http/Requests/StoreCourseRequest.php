<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && (
            auth()->user()->isInstructor() || auth()->user()->isAdmin()
        );
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'overview'    => ['nullable', 'string', 'max:500'],
            'category_id' => ['required', 'exists:categories,id'],
            'status'      => ['required', 'in:draft,published,archived'],
            // Accept either an uploaded file OR a URL string
            'thumbnail'   => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:2048'],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.exists' => 'Please select a valid category.',
            'thumbnail.image'    => 'The thumbnail must be an image file.',
            'thumbnail.max'      => 'The thumbnail may not be larger than 2MB.',
        ];
    }
}
