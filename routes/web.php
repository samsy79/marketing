<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonalizedMarketingController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/recommendations', [PersonalizedMarketingController::class, 'getRecommendations']);
    Route::post('/page-visit', [PersonalizedMarketingController::class, 'trackPageVisit'])->name('page.visit');
    Route::post('/abandoned-cart', [PersonalizedMarketingController::class, 'trackAbandonedCart'])->name('abandoned.cart');
    Route::post('/products/{productId}/update-price', [PersonalizedMarketingController::class, 'updateProductPrice'])->name('products.update.price');
    Route::post('/promotions', [PersonalizedMarketingController::class, 'createPromotion'])->name('promotions.create');
    Route::post('/campaigns', [PersonalizedMarketingController::class, 'createCampaign'])->name('campaigns.create');
    Route::post('/send-promotion-email', [PersonalizedMarketingController::class, 'sendPromotionEmail'])->name('send.promotion.email');
});

