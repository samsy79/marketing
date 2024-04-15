<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PersonalizedMarketingService;
use App\Models\Product;
use App\Models\Promotion;
use App\Models\Campaign;

class PersonalizedMarketingController extends Controller
{
    protected $personalizedMarketingService;

    public function __construct(PersonalizedMarketingService $personalizedMarketingService)
    {
        $this->personalizedMarketingService = $personalizedMarketingService;
    }

    public function getRecommendations(Request $request)
    {
        $user = $request->user();
        $this->personalizedMarketingService->trackUserBehavior($user, $request->url());

        $recommendations = $this->personalizedMarketingService->getPersonalizedRecommendations($user);
        return response()->json($recommendations);
    }

    public function trackPageVisit(Request $request)
    {
        $user = $request->user();
        $this->personalizedMarketingService->trackPageVisit($user, $request->url());
        return response()->json(['message' => 'Page visit tracked']);
    }

    public function trackAbandonedCart(Request $request)
    {
        $user = $request->user();
        $cart = $request->input('cart');
        $this->personalizedMarketingService->trackAbandonedCart($user, $cart);
        return response()->json(['message' => 'Abandoned cart tracked']);
    }

    public function updateProductPrice(Request $request, $productId)
    {
        $product = Product::findOrFail($productId);
        $newPrice = $request->input('new_price');
        $this->personalizedMarketingService->updateProductPrice($product, $newPrice);
        return response()->json(['message' => 'Product price updated']);
    }

    public function createPromotion(Request $request)
    {
        $promotion = new Promotion();
        $promotion->fill($request->all());
        $this->personalizedMarketingService->createPromotion($promotion);
        return response()->json(['message' => 'Promotion created']);
    }

    public function createCampaign(Request $request)
    {
        $campaign = new Campaign();
        $campaign->fill($request->all());
        $this->personalizedMarketingService->createCampaign($campaign);
        return response()->json(['message' => 'Campaign created']);
    }

    public function sendPromotionEmail(Request $request)
    {
        $productId = $request->input('product_id');
        $product = Product::findOrFail($productId);
        $this->personalizedMarketingService->sendPromotionEmail($product);
        return response()->json(['message' => 'Promotion email sent']);
    }
}