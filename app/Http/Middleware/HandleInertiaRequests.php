<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();
        $company = $user?->primaryCompany;

        $sharedUser = null;

        if ($user) {
            $sharedUser = [
                ...$user->toArray(),
                'roles' => $user->getRoleNames()->values()->all(),
                'permissions' => $user->getAllPermissions()
                    ->pluck('name')
                    ->values()
                    ->all(),
                'company' => $company ? [
                    'id' => $company->id,
                    'legal_name' => $company->legal_name,
                    'trading_name' => $company->trading_name,
                    'activity_type' => $company->activity_type?->value,
                    'verification_status' => $company->verification_status?->value,
                ] : null,
            ];
        }

        return [
            ...parent::share($request),
            'name' => config('app.name'),
            'auth' => [
                'user' => $sharedUser,
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
            'sidebarOpen' => ! $request->hasCookie('sidebar_state')
                || $request->cookie('sidebar_state') === 'true',
        ];
    }
}
