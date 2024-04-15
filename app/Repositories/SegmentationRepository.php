<?php

namespace App\Repositories;

use App\Models\User;
use App\Models\Segmentation;

class SegmentationRepository
{
    /**
     * Récupère une segmentation par son ID.
     *
     * @param  int  $segmentationId
     * @return \App\Models\Segmentation|null
     */
    public function getById($segmentationId)
    {
        return Segmentation::find($segmentationId);
    }

    public function getUserSegments(User $user)
    {
        // Implémentez la logique pour récupérer les segments de l'utilisateur à partir de la base de données
        // Cela pourrait être par exemple une relation entre l'utilisateur et les segments dans la base de données
        return $user->segments()->pluck('id')->toArray();
    }

    /**
     * Crée une nouvelle segmentation.
     *
     * @param  array  $data
     * @return \App\Models\Segmentation
     */
    public function create(array $data)
    {
        return Segmentation::create($data);
    }

    /**
     * Met à jour une segmentation existante.
     *
     * @param  int  $segmentationId
     * @param  array  $data
     * @return bool
     */
    public function update($segmentationId, array $data)
    {
        $segmentation = Segmentation::find($segmentationId);

        if ($segmentation) {
            return $segmentation->update($data);
        }

        return false;
    }

    /**
     * Supprime une segmentation.
     *
     * @param  int  $segmentationId
     * @return bool|null
     */
    public function delete($segmentationId)
    {
        $segmentation = Segmentation::find($segmentationId);

        if ($segmentation) {
            return $segmentation->delete();
        }

        return false;
    }

    /**
     * Récupère toutes les segmentations.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Segmentation::all();
    }
}
