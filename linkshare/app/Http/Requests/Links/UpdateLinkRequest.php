<?php

namespace App\Http\Requests\Links;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'is_favorite' => ['sometimes', 'boolean'],
            'note' => ['nullable', 'string'],
            'url' => ['sometimes', 'url', 'max:2048'],
            'title' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'string', 'max:2048'],
            'site_name' => ['nullable', 'string', 'max:255'],
            'domain' => ['nullable', 'string', 'max:255'],
        ];
    }
}
