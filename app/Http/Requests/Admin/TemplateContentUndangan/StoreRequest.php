<?php

namespace App\Http\Requests\Admin\TemplateContentUndangan;

use App\Rules\templateContentUndanganValidate;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return templateContentUndanganValidate::rules();
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return templateContentUndanganValidate::messages();
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return templateContentUndanganValidate::attributes();
    }
}
