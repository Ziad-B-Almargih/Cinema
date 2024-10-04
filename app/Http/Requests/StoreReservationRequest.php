<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReservationRequest extends FormRequest
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
            'movie_id' => ['required', 'exists:movies,id'],
            'standard_seats' => ['required', 'integer', 'min:0'],
            'vip_seats' => ['required', 'integer', 'min:0'],
            'consumables' => ['nullable', 'array'],
            'consumables.*.id' => ['required', 'integer', 'exists:consumables,id'],
            'consumables.*.quantity' => ['required', 'integer', 'min:0'],
        ];
    }
}
