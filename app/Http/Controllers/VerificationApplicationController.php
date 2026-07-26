<?php

namespace App\Http\Controllers;

use App\Enums\VerificationStatus;
use App\Http\Requests\CompanyApplicationRequest;
use App\Models\Company;
use App\Models\VerificationDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class VerificationApplicationController extends Controller
{
    public function create(): Response|RedirectResponse
    {
        $user = request()->user();
        $company = $user->primaryCompany;

        if ($company && ! in_array(
            $company->verification_status,
            [VerificationStatus::Draft, VerificationStatus::Rejected],
            true
        )) {
            return redirect()->route('verification.status');
        }

        $suggestedActivityType = match ($user->requested_role) {
            'buyer' => 'importer',
            'seller' => 'exporter',
            default => 'both',
        };

        return Inertia::render('Verification/Create', [
            'company' => $company,
            'suggestedActivityType' => $suggestedActivityType,
            'userEmail' => $user->email,
        ]);
    }

    public function store(CompanyApplicationRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->safe()->except('documents');

        $data['activity_type'] = match ($user->requested_role) {
            'buyer' => 'importer',
            'seller' => 'exporter',
            default => 'both',
        };

        DB::transaction(function () use ($request, $user, $data): void {
            $company = $user->primaryCompany;

            if (! $company) {
                $company = Company::create([
                    ...$data,
                    'owner_user_id' => $user->id,
                    'verification_status' => VerificationStatus::Draft,
                ]);

                $company->users()->attach($user->id, [
                    'job_title' => $user->job_title,
                    'is_owner' => true,
                    'status' => 'active',
                ]);

                $user->update(['primary_company_id' => $company->id]);
            } else {
                abort_unless(
                    in_array(
                        $company->verification_status,
                        [VerificationStatus::Draft, VerificationStatus::Rejected],
                        true
                    ),
                    422,
                    'This application can no longer be edited.'
                );

                $company->update($data);
            }

            foreach ($request->file('documents', []) as $index => $documentInput) {
                $file = $documentInput['file'];
                $path = $file->store(
                    "verification-documents/{$company->id}",
                    'local'
                );

                VerificationDocument::create([
                    'company_id' => $company->id,
                    'uploaded_by' => $user->id,
                    'document_type' => $request->input("documents.{$index}.type"),
                    'original_name' => $file->getClientOriginalName(),
                    'disk' => 'local',
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size_bytes' => $file->getSize(),
                    'status' => 'pending',
                ]);
            }

            $company->update([
                'verification_status' => VerificationStatus::Pending,
                'submitted_at' => now(),
                'rejection_reason' => null,
            ]);
        });

        return redirect()->route('verification.status')
            ->with('success', 'Your company verification application was submitted.');
    }

    public function status(): Response
    {
        return Inertia::render('Verification/Status', [
            'company' => request()->user()->primaryCompany?->load('documents'),
        ]);
    }
}
