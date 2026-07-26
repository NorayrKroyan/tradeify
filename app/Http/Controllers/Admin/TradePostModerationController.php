<?php

namespace App\Http\Controllers\Admin;

use App\Enums\TradePostStatus;
use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\TradePost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TradePostModerationController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = TradePost::query()
            ->with(['company:id,legal_name,trading_name', 'user:id,name,email', 'category:id,name', 'images'])
            ->when($request->string('status')->toString(), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/TradePosts/Index', ['posts' => $posts]);
    }

    public function decide(Request $request, TradePost $tradePost): RedirectResponse
    {
        $validated = $request->validate([
            'decision' => ['required', 'in:publish,reject,archive'],
            'admin_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $before = $tradePost->toArray();

        $status = match ($validated['decision']) {
            'publish' => TradePostStatus::Published,
            'reject' => TradePostStatus::Rejected,
            'archive' => TradePostStatus::Archived,
        };

        $tradePost->update([
            'status' => $status,
            'admin_notes' => $validated['admin_notes'] ?? null,
            'published_at' => $status === TradePostStatus::Published ? now() : null,
        ]);

        AuditLog::create([
            'causer_id' => $request->user()->id,
            'event' => "trade_post.{$validated['decision']}",
            'auditable_type' => TradePost::class,
            'auditable_id' => $tradePost->id,
            'old_values' => $before,
            'new_values' => $tradePost->fresh()->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with('success', 'Post moderation decision saved.');
    }
}
