<?php

namespace App\Repositories;

use App\Models\Campaign;
use Illuminate\Support\Carbon;

class CampaignRepository
{
    /**
     * Récupère une campagne par son ID.
     *
     * @param  int  $campaignId
     * @return \App\Models\Campaign|null
     */
    public function getById($campaignId)
    {
        return Campaign::find($campaignId);
    }

    public function getActivePersonalizedCampaigns(array $segmentIds)
    {
        return Campaign::where('is_active', true)
                       ->whereIn('segment_id', $segmentIds)
                       ->where('start_date', '<=', Carbon::now())
                       ->where('end_date', '>=', Carbon::now())
                       ->get();
    }

    /**
     * Crée une nouvelle campagne.
     *
     * @param  array  $data
     * @return \App\Models\Campaign
     */
    public function create(array $data)
    {
        return Campaign::create($data);
    }

    /**
     * Met à jour une campagne existante.
     *
     * @param  int  $campaignId
     * @param  array  $data
     * @return bool
     */
    public function update($campaignId, array $data)
    {
        $campaign = Campaign::find($campaignId);

        if ($campaign) {
            return $campaign->update($data);
        }

        return false;
    }

    /**
     * Supprime une campagne.
     *
     * @param  int  $campaignId
     * @return bool|null
     */
    public function delete($campaignId)
    {
        $campaign = Campaign::find($campaignId);

        if ($campaign) {
            return $campaign->delete();
        }

        return false;
    }

    /**
     * Récupère toutes les campagnes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Campaign::all();
    }
}
