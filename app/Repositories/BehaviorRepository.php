<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Behavior;
use App\Models\PageVisit;

class BehaviorRepository
{
    /**
     * Récupère un comportement par son ID.
     *
     * @param  int  $behaviorId
     * @return \App\Models\Behavior|null
     */
    public function getById($behaviorId)
    {
        return Behavior::find($behaviorId);
    }

    public function trackPageVisit(User $user, $url)
    {
        PageVisit::create([
            'user_id' => $user->id,
            'url' => $url,
        ]);
    }
    /**
     * Crée un nouveau comportement.
     *
     * @param  array  $data
     * @return \App\Models\Behavior
     */
    public function create(array $data)
    {
        return Behavior::create($data);
    }

    /**
     * Met à jour un comportement existant.
     *
     * @param  int  $behaviorId
     * @param  array  $data
     * @return bool
     */
    public function update($behaviorId, array $data)
    {
        $behavior = Behavior::find($behaviorId);

        if ($behavior) {
            return $behavior->update($data);
        }

        return false;
    }

    /**
     * Supprime un comportement.
     *
     * @param  int  $behaviorId
     * @return bool|null
     */
    public function delete($behaviorId)
    {
        $behavior = Behavior::find($behaviorId);

        if ($behavior) {
            return $behavior->delete();
        }

        return false;
    }

    /**
     * Récupère tous les comportements.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Behavior::all();
    }
}
