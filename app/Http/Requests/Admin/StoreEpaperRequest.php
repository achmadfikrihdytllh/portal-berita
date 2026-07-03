<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEpaperRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isEditor();
    }

    public function rules(): array
    {
        $epaperId = $this->route('epaper')?->id;

        return [
            'title'        => ['required', 'string', 'max:255'],
            'edition_date' => ['required', 'date', Rule::unique('epapers', 'edition_date')->ignore($epaperId)],
            'cover_image'  => [$epaperId ? 'nullable' : 'required', 'image', 'max:2048'],
            'file_path'    => [$epaperId ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'is_published' => ['boolean'],
        ];
    }
}