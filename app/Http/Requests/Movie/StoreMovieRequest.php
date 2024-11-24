<?php

namespace App\Http\Requests\Movie;

use App\Enums\MovieType;
use App\Models\Movie;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMovieRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'thumbnail' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],
            'schedule_id' => ['required', 'integer', 'exists:schedules,id'],
            'standard_price' => ['required', 'numeric', 'min:1'],
            'vip_price' => ['required', 'numeric', 'min:1'],
            'hall_id' => ['required', Rule::exists('halls', 'id')->withoutTrashed()],
            'type' => ['required', Rule::in(MovieType::values())],
            'trailers' => ['nullable', 'array'],
            'trailers.*' => ['required', 'file', 'mimes:mpeg,ogg,mp4,webm'],
        ];
    }
}
