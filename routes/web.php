<?php

use App\Http\Controllers\Admin\TradePostModerationController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\VerificationController as AdminVerificationController;
use App\Http\Controllers\OnboardingController;
use App\Http\Controllers\TradePostController;
use App\Http\Controllers\VerificationApplicationController;
use App\Http\Controllers\VerificationDocumentController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public marketplace
|--------------------------------------------------------------------------
*/

Route::get('/', [TradePostController::class, 'index'])->name('home');
Route::get('/trade-posts', [TradePostController::class, 'index'])
    ->name('trade-posts.index');

/*
|--------------------------------------------------------------------------
| Signed-in users: one clear setup dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function (): void {
    Route::get('/dashboard', OnboardingController::class)->name('dashboard');
    Route::get('/onboarding', OnboardingController::class)->name('onboarding');
});

/*
|--------------------------------------------------------------------------
| Email-verified users
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function (): void {
    Route::get(
        '/verification/apply',
        [VerificationApplicationController::class, 'create']
    )->name('verification.create');

    Route::post(
        '/verification/apply',
        [VerificationApplicationController::class, 'store']
    )->name('verification.store');

    Route::get(
        '/verification/status',
        [VerificationApplicationController::class, 'status']
    )->name('verification.status');

    Route::get(
        '/verification-documents/{document}/download',
        [VerificationDocumentController::class, 'download']
    )->name('verification-documents.download');

    Route::middleware([
        'verified.company',
        'permission:create trade posts',
    ])->group(function (): void {
        Route::get('/my-posts', [TradePostController::class, 'myPosts'])
            ->name('my-posts.index');

        Route::get('/trade-posts/create', [TradePostController::class, 'create'])
            ->name('trade-posts.create');

        Route::post('/trade-posts', [TradePostController::class, 'store'])
            ->name('trade-posts.store');
    });

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('permission:access admin panel')
        ->group(function (): void {
            Route::get(
                '/verifications',
                [AdminVerificationController::class, 'index']
            )->name('verifications.index');

            Route::get(
                '/verifications/{company}',
                [AdminVerificationController::class, 'show']
            )->name('verifications.show');

            Route::post(
                '/verifications/{company}/decision',
                [AdminVerificationController::class, 'decide']
            )->name('verifications.decide');

            Route::get(
                '/trade-posts',
                [TradePostModerationController::class, 'index']
            )->name('trade-posts.index');

            Route::post(
                '/trade-posts/{tradePost}/decision',
                [TradePostModerationController::class, 'decide']
            )->name('trade-posts.decide');

            Route::get('/users', [AdminUserController::class, 'index'])
                ->name('users.index');

            Route::put('/users/{user}', [AdminUserController::class, 'update'])
                ->name('users.update');
        });
});

Route::get('/trade-posts/{tradePost}', [TradePostController::class, 'show'])
    ->whereNumber('tradePost')
    ->name('trade-posts.show');

require __DIR__.'/settings.php';
