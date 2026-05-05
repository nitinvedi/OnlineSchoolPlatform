<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLessonRequest extends FormRequest
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
            'title'            => ['required', 'string', 'max:255'],
            'content'          => ['nullable', 'string'],
            'description'      => ['nullable', 'string', 'max:500'],
            'type'             => ['required', 'in:video,text,quiz,resource'],
            'video_url'        => ['nullable', 'string', 'max:500'],
            'video_file'       => ['nullable', 'file', 'mimes:mp4,mov,ogg,qt', 'max:102400'],
            'duration_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
            'is_free'          => ['boolean'],
            'order'            => ['nullable', 'integer', 'min:0'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_free' => $this->boolean('is_free'),
        ]);
    }
}
