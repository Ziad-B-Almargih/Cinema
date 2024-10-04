<?php

namespace App\Http\Requests\Movie;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMovieRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048', // Optional, max file size 2MB
            'showing_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'standard_price' => 'required|numeric|min:1',
            'vip_price' => 'required|numeric|min:1',
            'hall_id' => 'required|exists:halls,id',
            'type' => 'required|string',
            'actors' => 'nullable|array',
            'actors.*' => 'nullable|string|max:255',
            'trailers' => 'nullable|array',
            'trailers.*' => 'nullable|mimes:mp4,mov,ogg,qt|max:20000',
            'removed_videos' => ['nullable', 'array'],
            'removed_videos.*' => ['required', 'integer'],
        ];
    }

}
