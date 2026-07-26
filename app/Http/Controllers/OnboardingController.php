<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OnboardingController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $user = $request->user();
        $company = $user->primaryCompany?->loadCount('documents');
        $roles = $user->getRoleNames()->values()->all();

        return Inertia::render('Onboarding/Index', [
            'profile' => [
                'name' => $user->name,
                'email' => $user->email,
                'requested_role' => $user->requested_role,
                'email_verified' => $user->hasVerifiedEmail(),
                'roles' => $roles,
                'is_admin' => in_array('super-admin', $roles, true)
                    || in_array('administrator', $roles, true),
            ],
            'company' => $company ? [
                'id' => $company->id,
                'legal_name' => $company->legal_name,
                'activity_type' => $company->activity_type?->value,
                'verification_status' => $company->verification_status?->value,
                'rejection_reason' => $company->rejection_reason,
                'suspension_reason' => $company->suspension_reason,
                'documents_count' => $company->documents_count,
                'is_approved' => $company->verification_status === VerificationStatus::Approved
                    && $company->isApproved(),
            ] : null,
        ]);
    }
}
