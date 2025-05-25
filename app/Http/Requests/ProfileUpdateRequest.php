<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'location' => ['nullable', 'string', 'max:255'],
            'favorite_platform' => ['nullable', 'string', 'max:255'],
            'discord_username' => ['nullable', 'string', 'max:255'],
            'psn_username' => ['nullable', 'string', 'max:255'],
            'xbox_username' => ['nullable', 'string', 'max:255'],
            'steam_username' => ['nullable', 'string', 'max:255'],
            'nintendo_username' => ['nullable', 'string', 'max:255'],
        ];
    }
}
