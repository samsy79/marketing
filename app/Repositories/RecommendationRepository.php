<?php 

namespace App\Repositories;

use App\Models\User;
use App\Models\Product;
use App\Models\Campaign;
use Illuminate\Support\Facades\Cache;

class RecommendationRepository
{
    protected $segmentationRepository;
    protected $campaignRepository;

    public function __construct(SegmentationRepository $segmentationRepository, CampaignRepository $campaignRepository)
    {
        $this->segmentationRepository = $segmentationRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function getPersonalizedRecommendations(User $user)
    {
        $userSegments = $this->segmentationRepository->getUserSegments($user);
        
        $campaignRecommendations = $this->getCachedCampaignRecommendations($userSegments);
        $collaborativeRecommendations = $this->getCachedCollaborativeRecommendations($user);
        $contentBasedRecommendations = $this->getCachedContentBasedRecommendations($user);

        $recommendations = $campaignRecommendations
                            ->merge($collaborativeRecommendations)
                            ->merge($contentBasedRecommendations)
                            ->unique('product_id')
                            ->take(10);

        return $recommendations->toArray();
    }

    public function addCampaignRecommendations(Campaign $campaign)
    {
        $campaign->recommendations()->sync($this->getRecommendedProducts($campaign->segment_id, $campaign->product_ids));
        Cache::forget("campaign_recommendations_".$campaign->segment_id);
    }

    protected function getCachedCampaignRecommendations(array $segmentIds)
    {
        return Cache::remember("campaign_recommendations_".implode(',', $segmentIds), 3600, function () use ($segmentIds) {
            return $this->getCampaignRecommendations($segmentIds);
        });
    }

    protected function getCachedCollaborativeRecommendations(User $user)
    {
        // Ajout de la logique pour les recommandations collaboratives basées sur l'historique d'achat du client
        if ($user->purchases->isNotEmpty()) {
            return $this->getCollaborativeRecommendations($user);
        }
        return collect();
    }

    protected function getCachedContentBasedRecommendations(User $user)
    {
        return Cache::remember("content_based_recommendations_".$user->id, 3600, function () use ($user) {
            return $this->getContentBasedRecommendations($user);
        });
    }

    protected function getCampaignRecommendations(array $segmentIds)
    {
        $campaigns = $this->campaignRepository->getActivePersonalizedCampaigns($segmentIds);
        $recommendations = collect();
        foreach ($campaigns as $campaign) {
            $recommendations = $recommendations->merge($campaign->recommendations);
        }
        return $recommendations;
    }

    protected function getCollaborativeRecommendations(User $user)
    {
        // Récupérer l'historique d'achat du client
        $userPurchaseHistory = $user->purchases()->pluck('product_id');
    
        // Trouver d'autres utilisateurs ayant acheté des produits similaires
        $similarUsers = User::whereHas('purchases', function ($query) use ($userPurchaseHistory) {
            $query->whereIn('product_id', $userPurchaseHistory);
        })->where('id', '!=', $user->id)->get();
    
        // Combiner les achats des utilisateurs similaires
        $combinedPurchases = collect();
        foreach ($similarUsers as $similarUser) {
            $combinedPurchases = $combinedPurchases->merge($similarUser->purchases()->pluck('product_id'));
        }
    
        // Filtrer les produits déjà achetés par le client
        $recommendedProducts = $combinedPurchases->diff($userPurchaseHistory)->unique();
    
        return Product::whereIn('id', $recommendedProducts)->get();
    }
    
    protected function getContentBasedRecommendations(User $user)
    {
        // Récupérer les produits déjà achetés par le client
        $userPurchaseHistory = $user->purchases()->pluck('product_id');
    
        // Analyser les caractéristiques des produits achetés
        $userProducts = Product::whereIn('id', $userPurchaseHistory)->get();
        $userProductFeatures = $this->extractProductFeatures($userProducts);
    
        // Trouver d'autres produits similaires en fonction des caractéristiques
        $similarProducts = Product::whereNotIn('id', $userPurchaseHistory)
                                  ->where(function ($query) use ($userProductFeatures) {
                                      foreach ($userProductFeatures as $feature => $value) {
                                          $query->where($feature, $value);
                                      }
                                  })
                                  ->get();
    
        return $similarProducts;
    }
    
    protected function extractProductFeatures($products)
{
    $productFeatures = [];

    foreach ($products as $product) {
        // Exemple : Extraction des caractéristiques du produit
        $productFeatures[$product->id] = [
            'title' => $product->title,
            'description' => $product->description,
            'category' => $product->category,
            // Ajoutez d'autres caractéristiques pertinentes ici
        ];
    }

    return $productFeatures;
}

    
    protected function getRecommendedProducts($segmentId, array $productIds = [])
    {
        $query = Product::whereIn('id', $productIds);
        if ($segmentId) {
            $query->whereHas('segments', function ($q) use ($segmentId) {
                $q->where('segment_id', $segmentId);
            });
        }
        return $query->inRandomOrder()->limit(10)->pluck('id');
    }
}