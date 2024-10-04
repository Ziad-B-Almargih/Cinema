<?php

namespace App\Http\Requests\Hall;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateHallRequest extends FormRequest
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
        return [
            'name' => ['required', 'string', 'max:255', Rule::unique('halls', 'name')->withoutTrashed()->ignore(request('hall')->id)],
            'standard_seats' => ['required', 'integer', 'min:1'],
            'vip_seats' => ['required', 'integer', 'min:0'],
        ];
    }
}
