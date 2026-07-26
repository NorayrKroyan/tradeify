<?php

namespace App\Models;

use App\Enums\CompanyActivity;
use App\Enums\VerificationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_user_id', 'legal_name', 'trading_name', 'registration_number',
        'tax_number', 'activity_type', 'country_code', 'registered_address',
        'operating_address', 'website', 'business_email', 'business_phone',
        'description', 'verification_status', 'submitted_at', 'verified_at',
        'verification_expires_at', 'rejection_reason', 'suspension_reason',
        'additional_information',
    ];

    protected function casts(): array
    {
        return [
            'activity_type' => CompanyActivity::class,
            'verification_status' => VerificationStatus::class,
            'submitted_at' => 'datetime',
            'verified_at' => 'datetime',
            'verification_expires_at' => 'datetime',
            'additional_information' => 'array',
        ];
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['job_title', 'is_owner', 'status'])
            ->withTimestamps();
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VerificationDocument::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(VerificationReview::class);
    }

    public function tradePosts(): HasMany
    {
        return $this->hasMany(TradePost::class);
    }

    public function isApproved(): bool
    {
        return $this->verification_status === VerificationStatus::Approved
            && (!$this->verification_expires_at || $this->verification_expires_at->isFuture());
    }
}
