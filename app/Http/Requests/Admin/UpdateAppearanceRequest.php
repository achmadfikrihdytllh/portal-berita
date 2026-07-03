<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAppearanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->isAdmin();
    }

    public function rules(): array
    {
        return [
            'site_name'        => ['nullable', 'string', 'max:100'],
            'site_tagline'     => ['nullable', 'string', 'max:150'],
            'color_primary'    => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_secondary'  => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_header_bg'  => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_header_text' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_footer_bg'  => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'color_footer_text' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'logo'             => ['nullable', 'image', 'max:1024'],
            'favicon'          => ['nullable', 'image', 'max:512'],
            'social_facebook'  => ['nullable', 'url', 'max:255'],
            'social_twitter'   => ['nullable', 'url', 'max:255'],
            'social_instagram' => ['nullable', 'url', 'max:255'],
            'social_youtube'   => ['nullable', 'url', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'regex' => ':attribute harus berupa kode warna hex yang valid, contoh: #2563eb',
        ];
    }
}
