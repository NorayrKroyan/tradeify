<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()
            ->with([
                'roles:id,name',
                'primaryCompany:id,legal_name,verification_status',
            ])
            ->when(
                $request->string('q')->toString(),
                function ($query, string $search): void {
                    $query->where(function ($sub) use ($search): void {
                        $sub->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
                }
            )
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
        ]);
    }

    public function update(
        Request $request,
        User $user
    ): RedirectResponse {
        abort_if(
            $request->user()->id === $user->id
            && $request->boolean('is_active') === false,
            422,
            'You cannot deactivate your own account.'
        );

        abort_if(
            $user->hasRole('super-admin')
            && ! $request->user()->hasRole('super-admin'),
            403
        );

        $validated = $request->validate([
            'is_active' => ['required', 'boolean'],
            'roles' => ['required', 'array'],
            'roles.*' => [
                'string',
                Rule::in([
                    'administrator',
                    'buyer',
                    'seller',
                    'broker',
                ]),
            ],
        ]);

        if (in_array('administrator', $validated['roles'], true)) {
            abort_unless(
                $request->user()->hasRole('super-admin'),
                403
            );
        }

        $before = $user->load('roles')->toArray();
        $preserveSuperAdmin = $user->hasRole('super-admin');
        $roles = $validated['roles'];

        if ($preserveSuperAdmin) {
            $roles[] = 'super-admin';
        }

        $user->update([
            'is_active' => $validated['is_active'],
        ]);

        $user->syncRoles(
            array_values(array_unique($roles))
        );

        AuditLog::create([
            'causer_id' => $request->user()->id,
            'event' => 'user.updated_by_admin',
            'auditable_type' => User::class,
            'auditable_id' => $user->id,
            'old_values' => $before,
            'new_values' => $user->fresh()->load('roles')->toArray(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);

        return back()->with(
            'success',
            'User permissions and account status updated.'
        );
    }
}
