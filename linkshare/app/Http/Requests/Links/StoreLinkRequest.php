<?php

namespace App\Http\Requests\Links;

use Illuminate\Foundation\Http\FormRequest;

class StoreLinkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'links' => ['required', 'array', 'min:1'],
            'links.*.url' => ['required', 'url', 'max:2048'],
            'links.*.title' => ['nullable', 'string', 'max:255'],
            'links.*.description' => ['nullable', 'string'],
            'links.*.image_url' => ['nullable', 'string', 'max:2048'],
            'links.*.site_name' => ['nullable', 'string', 'max:255'],
            'links.*.domain' => ['nullable', 'string', 'max:255'],
            'links.*.note' => ['nullable', 'string'],
        ];
    }
}
