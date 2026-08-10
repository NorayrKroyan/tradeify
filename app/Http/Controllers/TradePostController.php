<?php

namespace App\Http\Controllers;

use App\Enums\TradePostStatus;
use App\Http\Requests\StoreTradePostRequest;
use App\Models\Category;
use App\Models\TradePost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class TradePostController extends Controller
{
    public function index(Request $request): Response
    {
        $posts = TradePost::query()
            ->published()
            ->with([
                'company:id,legal_name,trading_name,verification_status,country_code',
                'category:id,name',
                'images',
            ])
            ->when(
                $request->string('q')->toString(),
                fn ($query, $search) => $query->where(function ($subQuery) use ($search): void {
                    $subQuery->where('title', 'like', "%{$search}%")
                        ->orWhere('product_name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                })
            )
            ->when(
                $request->string('type')->toString(),
                fn ($query, $type) => $query->where('type', $type)
            )
            ->when(
                $request->integer('category_id'),
                fn ($query, $categoryId) => $query->where('category_id', $categoryId)
            )
            ->latest('published_at')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('TradePosts/Index', [
            'posts' => $posts,
            'filters' => $request->only(['q', 'type', 'category_id']),
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function create(Request $request): Response
    {
        $allowedTypes = $this->allowedPostTypes($request);
        $requestedType = $request->string('type')->toString();
        $defaultType = in_array($requestedType, $allowedTypes, true)
            ? $requestedType
            : $allowedTypes[0];

        return Inertia::render('TradePosts/Create', [
            'categories' => Category::query()
                ->where('is_active', true)
                ->orderBy('name')
                ->get(['id', 'name']),
            'allowedTypes' => $allowedTypes,
            'defaultType' => $defaultType,
        ]);
    }

    public function store(StoreTradePostRequest $request): RedirectResponse
    {
        $validated = $request->safe()->except('images');
        $user = $request->user();
        $allowedTypes = $this->allowedPostTypes($request);

        abort_unless(
            in_array($validated['type'], $allowedTypes, true),
            403,
            'Your approved account role does not allow this type of post.'
        );

        DB::transaction(function () use ($request, $validated, $user): void {
            if ($validated['type'] !== 'broker') {
                $validated['broker_direction'] = null;
                $validated['broker_commission_type'] = null;
                $validated['broker_commission_value'] = null;
            }

            $post = TradePost::create([
                ...$validated,
                'company_id' => $user->primary_company_id,
                'user_id' => $user->id,
                'status' => TradePostStatus::Pending,
            ]);

            foreach ($request->file('images', []) as $index => $image) {
                $path = $image->store("trade-posts/{$post->id}", 'public');

                $post->images()->create([
                    'disk' => 'public',
                    'path' => $path,
                    'alt_text' => $post->product_name,
                    'sort_order' => $index,
                ]);
            }
        });

        return redirect()->route('my-posts.index')
            ->with('success', 'Your post was sent to the administrator for review.');
    }

    public function show(TradePost $tradePost): Response
    {
        abort_unless(
            $tradePost->status === TradePostStatus::Published
            || request()->user()?->can('view', $tradePost),
            404
        );

        return Inertia::render('TradePosts/Show', [
            'post' => $tradePost->load(['company', 'category', 'images']),
        ]);
    }

    public function myPosts(Request $request): Response
    {
        $posts = TradePost::query()
            ->where('user_id', $request->user()->id)
            ->with(['category:id,name', 'images'])
            ->latest()
            ->paginate(20);

        return Inertia::render('TradePosts/MyPosts', [
            'posts' => $posts,
        ]);
    }

    /**
     * @return list<string>
     */
    private function allowedPostTypes(Request $request): array
    {
        $user = $request->user();
        $types = [];

        if ($user->hasRole('buyer')) {
            $types[] = 'buy';
        }

        if ($user->hasRole('seller')) {
            $types[] = 'sell';
        }

        if ($user->hasRole('broker')) {
            $types[] = 'broker';
        }

        if ($user->hasAnyRole(['super-admin', 'administrator'])) {
            return ['buy', 'sell', 'broker'];
        }

        abort_if(
            $types === [],
            403,
            'No approved buyer, seller, or broker role is assigned.'
        );

        return $types;
    }
}
