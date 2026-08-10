<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationLicense extends Model
{
    protected $fillable = [
        'user_id',
        'marketplace_role',
        'license_type',
        'original_name',
        'disk',
        'path',
        'mime_type',
        'size_bytes',
        'status',
        'verification_document_id',
        'transferred_at',
    ];

    protected function casts(): array
    {
        return [
            'transferred_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function verificationDocument(): BelongsTo
    {
        return $this->belongsTo(VerificationDocument::class);
    }
}
