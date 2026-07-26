<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TradePostImage extends Model
{
    use HasFactory;

    protected $fillable = ['trade_post_id', 'disk', 'path', 'alt_text', 'sort_order'];

    public function tradePost(): BelongsTo
    {
        return $this->belongsTo(TradePost::class);
    }
}
