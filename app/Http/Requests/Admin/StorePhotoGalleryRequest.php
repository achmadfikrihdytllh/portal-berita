<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StorePhotoGalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAuthor();
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'category_id'   => ['nullable', 'exists:categories,id'],
            'description'   => ['nullable', 'string', 'max:1000'],
            'cover_image'   => ['nullable', 'image', 'max:2048'],
            'status'        => ['required', 'in:draft,published'],
            'images'        => ['nullable', 'array'],
            'images.*'      => ['image', 'max:3072'],
            'captions'      => ['nullable', 'array'],
            'captions.*'    => ['nullable', 'string', 'max:255'],
        ];
    }
}