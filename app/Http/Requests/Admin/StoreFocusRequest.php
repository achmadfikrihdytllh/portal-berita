<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreFocusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isEditor();
    }

    public function rules(): array
    {
        return [
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string', 'max:1000'],
            'cover_image'  => ['nullable', 'image', 'max:2048'],
            'is_active'    => ['boolean'],
            'news_ids'     => ['nullable', 'array'],
            'news_ids.*'   => ['exists:news,id'],
        ];
    }
}