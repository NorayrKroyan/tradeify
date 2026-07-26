<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, string> $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'requested_role' => [
                'required',
                Rule::in(['buyer', 'seller', 'both']),
            ],
            'password' => $this->passwordRules(),
        ], [
            'requested_role.required' => 'Please choose whether you want to buy, sell, or do both.',
            'requested_role.in' => 'Please choose a valid marketplace role.',
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'requested_role' => $input['requested_role'],
            'password' => $input['password'],
        ]);
    }
}
