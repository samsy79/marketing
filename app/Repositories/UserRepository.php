<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * Récupère un utilisateur par son ID.
     *
     * @param  int  $userId
     * @return \App\Models\User|null
     */
    public function getById($userId)
    {
        return User::find($userId);
    }

    public function getUsersBySegments($segmentIds)
    {
        return User::whereHas('segments', function ($query) use ($segmentIds) {
            $query->whereIn('segment_id', $segmentIds);
        })->get();
    }
    
    public function getUsersByProduct($productId)
    {
        return User::whereHas('purchases', function ($query) use ($productId) {
            $query->where('product_id', $productId);
        })->get();
    }

    /**
     * Crée un nouvel utilisateur.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    public function create(array $data)
    {
        return User::create($data);
    }

    /**
     * Met à jour un utilisateur existant.
     *
     * @param  int  $userId
     * @param  array  $data
     * @return bool
     */
    public function update($userId, array $data)
    {
        $user = $this->getById($userId);

        if ($user) {
            return $user->update($data);
        }

        return false;
    }

    /**
     * Supprime un utilisateur.
     *
     * @param  int  $userId
     * @return bool|null
     */
    public function delete($userId)
    {
        $user = $this->getById($userId);

        if ($user) {
            return $user->delete();
        }

        return false;
    }

    /**
     * Récupère tous les utilisateurs.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return User::all();
    }

    // Ajoutez d'autres méthodes de gestion des utilisateurs au besoin...
}
