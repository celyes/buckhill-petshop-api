<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string'],
            'price' => ['required', 'numeric'],
            'description' => ['required', 'string'],
            'image' => ['required', 'string'],
            'brand' => ['required', 'string'],
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'metadata' => [
                'image' => $this->image,
                'brand' => $this->brand,
            ]
        ]);
    }
}
