<?php

namespace App\Http\Requests\Collections;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->route('collection')->user_id === $this->user()->id;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'color' => ['nullable', 'string', 'regex:/^#[0-9A-Fa-f]{6}$/'],
            'image' => ['nullable', 'image', 'mimes:jpeg,png', 'max:2048'],
            'links' => ['nullable', 'array'],
            'links.*.url' => ['required', 'url', 'max:2048'],
            'links.*.title' => ['nullable', 'string', 'max:255'],
            'links.*.description' => ['nullable', 'string'],
            'links.*.image_url' => ['nullable', 'string', 'max:2048'],
            'links.*.site_name' => ['nullable', 'string', 'max:255'],
            'links.*.domain' => ['nullable', 'string', 'max:255'],
        ];
    }
}
