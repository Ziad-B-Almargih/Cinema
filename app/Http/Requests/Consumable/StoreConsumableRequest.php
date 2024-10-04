<?php

namespace App\Http\Requests\Consumable;

use App\Enums\ConsumableType;
use App\Models\Consumable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreConsumableRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', Rule::unique('consumables', 'name')->withoutTrashed()],
            'price' => ['required', 'numeric'],
            'type' => ['required', 'string', Rule::in(ConsumableType::values())],
        ];
    }
}
