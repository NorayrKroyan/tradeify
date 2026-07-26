<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'reviewer_id', 'decision', 'notes',
        'checks', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return ['checks' => 'array'];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}
