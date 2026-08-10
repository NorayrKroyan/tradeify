<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\RegistrationLicense;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param array<string, mixed> $input
     */
    public function create(array $input): User
    {
        $requestedRole = $input['requested_role'] ?? null;

        $allowedLicenseTypes = match ($requestedRole) {
            'buyer' => [
                'business_license',
                'import_license',
                'trade_license',
                'other_license',
            ],
            'seller' => [
                'business_license',
                'export_license',
                'manufacturing_license',
                'quality_certificate',
                'other_license',
            ],
            'both' => [
                'business_license',
                'import_export_license',
                'manufacturing_license',
                'quality_certificate',
                'other_license',
            ],
            'broker' => [
                'broker_license',
                'intermediary_mandate',
                'business_license',
                'other_license',
            ],
            default => [
                'business_license',
                'import_license',
                'export_license',
                'import_export_license',
                'trade_license',
                'manufacturing_license',
                'quality_certificate',
                'broker_license',
                'intermediary_mandate',
                'other_license',
            ],
        };

        Validator::make($input, [
            ...$this->profileRules(),
            'requested_role' => [
                'required',
                Rule::in(['buyer', 'seller', 'both', 'broker']),
            ],
            'licenses' => ['required', 'array', 'min:1', 'max:5'],
            'licenses.*.type' => [
                'required',
                Rule::in($allowedLicenseTypes),
            ],
            'licenses.*.file' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:10240',
            ],
            'password' => $this->passwordRules(),
        ], [
            'requested_role.required' => 'Please choose whether you want to buy, sell, do both, or act as a broker.',
            'requested_role.in' => 'Please choose a valid marketplace role.',
            'licenses.required' => 'Please upload at least one business license or authorization document.',
            'licenses.min' => 'Please upload at least one business license or authorization document.',
            'licenses.*.type.required' => 'Please select the license type.',
            'licenses.*.type.in' => 'The selected license type is not valid for this marketplace role.',
            'licenses.*.file.required' => 'Please choose a license file.',
            'licenses.*.file.mimes' => 'License files must be PDF, JPG, JPEG, or PNG.',
            'licenses.*.file.max' => 'Each license file may not be larger than 10 MB.',
        ])->validate();

        return DB::transaction(function () use ($input): User {
            $user = User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'requested_role' => $input['requested_role'],
                'password' => $input['password'],
            ]);

            foreach ($input['licenses'] as $licenseInput) {
                $file = $licenseInput['file'];
                $path = $file->store("registration-licenses/{$user->id}", 'local');

                RegistrationLicense::create([
                    'user_id' => $user->id,
                    'marketplace_role' => $input['requested_role'],
                    'license_type' => $licenseInput['type'],
                    'original_name' => $file->getClientOriginalName(),
                    'disk' => 'local',
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'status' => 'pending',
                ]);
            }

            return $user;
        });
    }
}
