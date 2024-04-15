<?php

namespace App\Services;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Product;
use App\Models\Campaign;
use App\Models\Promotion;
use Illuminate\Http\Client\Request;
use App\Repositories\CartRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Mail;
use App\Repositories\SalesRepository;
use App\Repositories\ProductRepository;
use App\Repositories\BehaviorRepository;
use App\Repositories\CampaignRepository;
use App\Repositories\DiscountRepository;
use App\Repositories\SegmentationRepository;
use App\Repositories\RecommendationRepository;

class PersonalizedMarketingService
{
    protected $userRepository;
    protected $productRepository;
    protected $behaviorRepository;
    protected $recommendationRepository;
    protected $segmentationRepository;
    protected $campaignRepository;
    protected $discountRepository;
    protected $cartRepository;
    protected $salesRepository;

    public function __construct(
        UserRepository $userRepository,
        ProductRepository $productRepository,
        BehaviorRepository $behaviorRepository,
        RecommendationRepository $recommendationRepository,
        SegmentationRepository $segmentationRepository,
        CampaignRepository $campaignRepository,
        DiscountRepository $discountRepository,
        CartRepository $cartRepository,
        SalesRepository $salesRepository
    ) {
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->behaviorRepository = $behaviorRepository;
        $this->recommendationRepository = $recommendationRepository;
        $this->segmentationRepository = $segmentationRepository;
        $this->campaignRepository = $campaignRepository;
        $this->discountRepository = $discountRepository;
        $this->cartRepository = $cartRepository;
        $this->salesRepository = $salesRepository;
    }


    public function getPersonalizedRecommendations(User $user)
    {
        $this->trackUserBehavior($user);
        $recommendations = $this->recommendationRepository->getPersonalizedRecommendations($user);
        return $recommendations;
    }

    public function createPromotion(Promotion $promotion)
    {
        $promotion->save();
        $this->sendPromotionNotifications($promotion);
    }

    public function createCampaign(Campaign $campaign)
    {
        $campaign->save();
        $this->activateCampaignRecommendations($campaign);
    }

    protected function sendPromotionNotifications(Promotion $promotion)
    {
        $users = $this->userRepository->getUsersBySegments($promotion->segments);
        foreach ($users as $user) {
            $this->sendPromotionEmail($user, $promotion->product, $promotion->discountPercentage, now(), now()->addDays(7));
        }
    }


    protected function activateCampaignRecommendations(Campaign $campaign)
    {
        $this->recommendationRepository->addCampaignRecommendations($campaign);
    }

  // App\Services\PersonalizedMarketingService
  public function sendPromotionEmail(Request $request)
  {
      $productId = $request->input('product_id');
      $product = Product::findOrFail($productId);
      $discountPercentage = $request->input('discount_percentage');
      $startsAt = Carbon::parse($request->input('starts_at'));
      $endsAt = Carbon::parse($request->input('ends_at'));

      // Envoyer l'email de promotion directement depuis le contrôleur
      $this->sendPromotionEmailNow($product, $discountPercentage, $startsAt, $endsAt);
      
      return response()->json(['message' => 'Promotion email sent']);
  }

  protected function sendPromotionEmailNow(Product $product, $discountPercentage, Carbon $startsAt, Carbon $endsAt)
{
    // Récupérer tous les utilisateurs pour lesquels envoyer la promotion
    $users = User::all();

    // Itérer sur chaque utilisateur et envoyer l'email de promotion
    foreach ($users as $user) {
        // Personnalisez le contenu de l'email comme vous le souhaitez
        $emailContent = "Cher {$user->name},\n\n";
        $emailContent .= "Nous avons une promotion spéciale pour vous !\n";
        $emailContent .= "Produit: {$product->name}\n";
        $emailContent .= "Réduction: {$discountPercentage}%\n";
        $emailContent .= "Valable du: {$startsAt->format('Y-m-d')} au {$endsAt->format('Y-m-d')}\n";
        $emailContent .= "\n\nMerci de votre fidélité !";

        // Envoyer l'email à l'utilisateur
        Mail::raw($emailContent, function ($message) use ($user) {
            $message->to($user->email)->subject('Promotion spéciale !');
        });
    }
}


    public function trackUserBehavior(User $user, $url = null)
    {
        if ($url) {
            $this->trackPageVisit($user, $url);
        }
        // Ajout de la méthode trackAbandonedCart pour le suivi du comportement utilisateur
        $this->trackAbandonedCart($user);
    }

    public function trackPageVisit(User $user, $url)
    {
        $this->behaviorRepository->trackPageVisit($user, $url);
    }

    public function trackAbandonedCart(User $user)
    {
        $cart = $this->cartRepository->getAbandonedCart($user);
        if ($cart) {
            $this->sendAbandonedCartEmail($user, $cart);
        }
    }

    protected function sendAbandonedCartEmail(User $user, $cart)
    {
        $products = $this->productRepository->getProductsByIds(array_keys($cart));
        Mail::send('emails.abandoned_cart', [
            'user' => $user,
            'products' => $products
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Finaliser votre achat');
        });
    }

    public function updateProductPrice(Product $product, $newPrice)
    {
        $this->discountRepository->updateProductPrice($product, $newPrice);
        // Ajout de la méthode sendPriceDropEmail pour informer les utilisateurs d'une baisse de prix
        $this->sendPriceDropEmail($product, $newPrice, $product->price);
    }

  // App\Services\PersonalizedMarketingService

protected function sendPriceDropEmail(Product $product, $currentPrice, $previousPrice)
{
    $users = $this->userRepository->getUsersByProduct($product->id);
    foreach ($users as $user) {
        $this->sendPromotionEmail($user, $product, $currentPrice, $previousPrice);
    }
}

}
