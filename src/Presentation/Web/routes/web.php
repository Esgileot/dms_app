<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Presentation\Web\App\Http\Controllers\Auth\{
    RegisterPageController,
    RegisterController,
    LoginPageController,
    LoginController,
    LogoutController,
};
use Presentation\Web\App\Http\Controllers\Auth\EmailVerification\{
    EmailVerificationPromptController,
    EmailVerificationNotificationController,
    VerifyEmailController
};
use Presentation\Web\App\Http\Controllers\Dashboard\DashboardPageController;
use Presentation\Web\App\Http\Controllers\Monitor\{
    CreateMonitorDomainPageController,
    CreateMonitorDomainController,
    UpdateMonitorDomainPageController,
    UpdateMonitorDomainController,
    DeleteMonitorDomainController,
    GetMonitorDomainDetailPageController
};

Route::get('/', fn() => to_route('web.login.page'));

Route::middleware('guest')->group(function () {
    Route::get('register', RegisterPageController::class)->name('register.page');
    Route::post('register', RegisterController::class)->name('register');

    Route::get('login', LoginPageController::class)->name('login.page');
    Route::post('login', LoginController::class)->name('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', EmailVerificationNotificationController::class)
        ->middleware('throttle:6,1')
        ->name('verification.send');


    Route::middleware(['verified'])->group(function () {
        Route::get('dashboard', DashboardPageController::class)->name('dashboard.page');

        Route::prefix('domains')->name('domains.')->group(function () {
            Route::get('/create', CreateMonitorDomainPageController::class)->name('create.page');
            Route::post('/', CreateMonitorDomainController::class)->name('create');

            Route::get('{domain_id}/details', GetMonitorDomainDetailPageController::class)->name('details.page');
            Route::get('/{domain_id}/update', UpdateMonitorDomainPageController::class)->name('update.page');
            Route::put('/{domain_id}', UpdateMonitorDomainController::class)->name('update');
            Route::delete('/{domain_id}', DeleteMonitorDomainController::class)->name('delete');
        });
    });

    Route::post('logout', LogoutController::class)->name('logout');
});
