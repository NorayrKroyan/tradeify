<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VerificationDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id', 'uploaded_by', 'document_type', 'original_name',
        'disk', 'path', 'mime_type', 'size_bytes', 'status',
        'review_notes', 'reviewed_by', 'reviewed_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'expires_at' => 'date',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
