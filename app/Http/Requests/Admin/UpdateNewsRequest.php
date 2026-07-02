<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateNewsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $news = $this->route('news');

        // Author hanya boleh edit berita miliknya sendiri; editor/admin bebas.
        if ($this->user()->role === 'author') {
            return $news->user_id === $this->user()->id;
        }

        return $this->user()->isAuthor();
    }

    public function rules(): array
    {
        return [
            'title'             => ['required', 'string', 'max:255'],
            'category_id'       => ['required', 'exists:categories,id'],
            'excerpt'           => ['nullable', 'string', 'max:500'],
            'content'           => ['required', 'string'],
            'thumbnail'         => ['nullable', 'image', 'max:2048'],
            'status'            => ['required', 'in:draft,published,archived'],
            'is_headline'       => ['boolean'],
            'is_breaking'       => ['boolean'],
            'meta_title'        => ['nullable', 'string', 'max:255'],
            'meta_description'  => ['nullable', 'string', 'max:500'],
            'tags'              => ['nullable', 'array'],
            'tags.*'            => ['exists:tags,id'],
        ];
    }
}
