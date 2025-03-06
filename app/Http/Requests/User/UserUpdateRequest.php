<?php

namespace App\Http\Requests\User;

use App\Enums\UserRoles;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends UserStoreRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['email'],
            'password' => ['string'],
            'role' => [Rule::enum(UserRoles::class)],
        ];
    }
}
