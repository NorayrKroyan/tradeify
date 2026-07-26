<?php

namespace App\Http\Controllers\Admin;

use App\Enums\CompanyActivity;
use App\Enums\VerificationStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\VerificationDecisionRequest;
use App\Models\AuditLog;
use App\Models\Company;
use App\Models\VerificationReview;
use App\Notifications\CompanyVerificationDecisionNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class VerificationController extends Controller
{
    public function index(Request $request): Response
    {
        $companies = Company::query()
            ->with('owner:id,name,email')
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('verification_status', $status))
            ->orderByRaw("FIELD(verification_status, 'pending', 'under_review', 'rejected', 'approved', 'suspended', 'draft')")
            ->latest('submitted_at')
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Verifications/Index', [
            'companies' => $companies,
            'filters' => $request->only('status'),
        ]);
    }

    public function show(Company $company): Response
    {
        if ($company->verification_status === VerificationStatus::Pending) {
            $company->update(['verification_status' => VerificationStatus::UnderReview]);
        }

        return Inertia::render('Admin/Verifications/Show', [
            'company' => $company->load(['owner', 'users', 'documents', 'reviews.reviewer']),
        ]);
    }

    public function decide(VerificationDecisionRequest $request, Company $company): RedirectResponse
    {
        $before = $company->toArray();
        $decision = $request->string('decision')->toString();

        DB::transaction(function () use ($request, $company, $decision, $before): void {
            if ($decision === 'approve') {
                $company->update([
                    'verification_status' => VerificationStatus::Approved,
                    'verified_at' => now(),
                    'verification_expires_at' => now()->addYear(),
                    'rejection_reason' => null,
                    'suspension_reason' => null,
                ]);

                $roles = match ($company->activity_type) {
                    CompanyActivity::Importer => ['buyer'],
                    CompanyActivity::Exporter => ['seller'],
                    CompanyActivity::Both => ['buyer', 'seller'],
                };

                foreach ($company->users as $user) {
                    $user->removeRole('buyer');
                    $user->removeRole('seller');
                    $user->assignRole($roles);
                }
            }

            if ($decision === 'reject') {
                $company->update([
                    'verification_status' => VerificationStatus::Rejected,
                    'rejection_reason' => $request->input('notes'),
                    'verified_at' => null,
                ]);
            }

            if ($decision === 'suspend') {
                $company->update([
                    'verification_status' => VerificationStatus::Suspended,
                    'suspension_reason' => $request->input('notes'),
                ]);

                foreach ($company->users as $user) {
                    $user->removeRole('buyer');
                    $user->removeRole('seller');
                }
            }

            VerificationReview::create([
                'company_id' => $company->id,
                'reviewer_id' => $request->user()->id,
                'decision' => $decision,
                'notes' => $request->input('notes'),
                'checks' => $request->input('checks', []),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            AuditLog::create([
                'causer_id' => $request->user()->id,
                'event' => "company.verification.{$decision}",
                'auditable_type' => Company::class,
                'auditable_id' => $company->id,
                'old_values' => $before,
                'new_values' => $company->fresh()->toArray(),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'created_at' => now(),
            ]);
        });

        $company->owner->notify(new CompanyVerificationDecisionNotification($company->fresh(), $decision));

        return redirect()->route('admin.verifications.index')
            ->with('success', "Company verification decision: {$decision}.");
    }
}
