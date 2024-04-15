<?php

namespace App\Repositories;

use App\Models\Sale;

class SalesRepository
{
    /**
     * Récupère une vente par son ID.
     *
     * @param  int  $saleId
     * @return \App\Models\Sale|null
     */
    public function getById($saleId)
    {
        return Sale::find($saleId);
    }

    /**
     * Crée une nouvelle vente.
     *
     * @param  array  $data
     * @return \App\Models\Sale
     */
    public function create(array $data)
    {
        return Sale::create($data);
    }

    /**
     * Met à jour une vente existante.
     *
     * @param  int  $saleId
     * @param  array  $data
     * @return bool
     */
    public function update($saleId, array $data)
    {
        $sale = Sale::find($saleId);

        if ($sale) {
            return $sale->update($data);
        }

        return false;
    }

    /**
     * Supprime une vente.
     *
     * @param  int  $saleId
     * @return bool|null
     */
    public function delete($saleId)
    {
        $sale = Sale::find($saleId);

        if ($sale) {
            return $sale->delete();
        }

        return false;
    }

    /**
     * Récupère toutes les ventes.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Sale::all();
    }
}
