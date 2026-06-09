<?php

namespace App\Http\Requests;

use App\Enums\MemberStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /** @return array<string, mixed> */
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'birth_date' => ['nullable', 'date', 'before_or_equal:today'],
            'gender' => ['nullable', 'string', 'max:20'],
            'profession' => ['required', 'string', 'max:100'],
            'phone' => ['nullable', 'string', 'max:30'],
            'address_description' => ['nullable', 'string'],
            'department' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', Rule::enum(MemberStatus::class)],
            'password' => ['nullable', 'string', 'min:4'],
            'photo' => ['nullable', function ($attribute, $value, $fail) {
                if (!is_string($value) && !($value instanceof \Illuminate\Http\UploadedFile)) {
                    $fail("Le champ photo doit être une image ou une chaîne de caractères.");
                }
            }],
        ];
    }
}
