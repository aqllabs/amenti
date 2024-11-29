<?php

use App\Http\Controllers\Auth\MagicLinkController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OgImageController;
use App\Http\Controllers\Payments\StripeController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\EnsureHasTeam;
use App\Http\Middleware\Subscribed;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('sitemap', [SitemapController::class, 'index'])->name('sitemap');

// Demo Coming Soon Page
Route::get('coming-soon', function () {
    return view('pages.coming-soon');
})->name('coming-soon');

Route::prefix('auth')->group(function () {
    Route::get('/redirect/{driver}', [SocialiteController::class, 'redirect'])
        ->name('socialite.redirect');
    Route::get('/callback/{driver}', [SocialiteController::class, 'callback'])
        ->name('socialite.callback');

    // Magic Links
    Route::post('/magic-link', [MagicLinkController::class, 'sendMagicLink'])->name('magic.link');
    Route::get('/magic-link/{token}', [MagicLinkController::class, 'loginWithMagicLink'])->name('magic.link.login');
});

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{article:slug}', [BlogController::class, 'article'])->name('blog.article');

// Dynamic Open Graph Image
Route::get('og-image/{title?}/{description?}', OgImageController::class)->name('og-image');

// For testing and modifying the default image template
Route::get('og-image-testing', function () {
    return view('seo.image', [
        'title' => 'Your dynamic og image',
        'description' => 'Your dynamic og image description', // optional
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    EnsureHasTeam::class
])->group(function () {

    //     Stripe Routes
    Route::prefix('stripe')->name('stripe.')->group(function () {
        Route::get('subscription-checkout/{price}', [StripeController::class, 'subscriptionCheckout'])->name('subscription.checkout');
        // If your product checkout does not require auth user,
        // move this part outside "auth:sanctum" middleware and change the logic inside method
        Route::get('product-checkout/{price}', [StripeController::class, 'productCheckout'])->name('product.checkout');
        Route::get('success', [StripeController::class, 'success'])->name('success');
        Route::get('error', [StripeController::class, 'error'])->name('error');
        Route::get('billing', [StripeController::class, 'billing'])->name('billing'); // Redirects to Customer Portal
    });
    Route::middleware([Subscribed::class])->group(function () {
        // Add endpoints that are only for subscribed users
    });

    Volt::route('/dashboard', 'dashboard')->name('dashboard');
    Volt::route('/activities', 'activities/activities')->name('activities.index');
    Volt::route('/activities/{activity}', 'activities/activity')->name('activity.show');

    Volt::route('/meetings', 'meetings/meetings')->name('meetings');
    Volt::route('/meetings/{meeting}', 'meetings/meeting')->name('meeting.show');

    Volt::route('/forms', 'forms/forms')->name('forms');
    Volt::route('/forms/{myForm}', 'forms/form')->name('form.show');

    Volt::route('academy', 'academy.academy')
        ->middleware(['auth'])

        ->name('academy.index');

    Volt::route('academy/{course}', 'academy.course')
        ->middleware(['auth'])
        ->name('academy.course.show');

    Volt::route('academy/{course}/lesson/{lesson}', 'academy.lesson')
        ->middleware(['auth'])
        ->name('academy.lesson.show');

    Volt::route('/ai-chat', 'ai-chat')->name('ai-chat');

    Volt::route('/manage-availability', 'meetings/manage-availability')->name('manage-availability');

    Volt::route('/mentorship', 'mentorship/mentorship-dashboard')->name('mentorship.dashboard');
    Volt::route('/mentorship/{user}', 'mentorship/mentorship-detail')->name('mentorship.detail');
});

// Demo Coming Soon Page
Route::get('flux', function () {
    return view('pages.flux');
})->name('flux');
