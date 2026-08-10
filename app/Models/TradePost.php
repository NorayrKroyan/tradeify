<?php

namespace App\Models;

use App\Enums\TradePostStatus;
use App\Enums\TradePostType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TradePost extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'category_id',
        'type',
        'broker_direction',
        'status',
        'title',
        'product_name',
        'description',
        'hs_code',
        'quantity',
        'unit',
        'price',
        'target_price',
        'currency',
        'broker_commission_type',
        'broker_commission_value',
        'price_terms',
        'incoterm',
        'origin_country_code',
        'destination_country_code',
        'minimum_order_quantity',
        'available_from',
        'expires_at',
        'custom_fields',
        'admin_notes',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => TradePostType::class,
            'status' => TradePostStatus::class,
            'quantity' => 'decimal:3',
            'price' => 'decimal:2',
            'target_price' => 'decimal:2',
            'broker_commission_value' => 'decimal:4',
            'minimum_order_quantity' => 'decimal:3',
            'available_from' => 'date',
            'expires_at' => 'date',
            'custom_fields' => 'array',
            'published_at' => 'datetime',
        ];
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', TradePostStatus::Published->value)
            ->where(function (Builder $q): void {
                $q->whereNull('expires_at')
                    ->orWhereDate('expires_at', '>=', now()->toDateString());
            });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(TradePostImage::class)->orderBy('sort_order');
    }
}
