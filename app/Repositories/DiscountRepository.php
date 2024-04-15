<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Discount;

class DiscountRepository
{
    /**
     * Récupère une remise par son ID.
     *
     * @param  int  $discountId
     * @return \App\Models\Discount|null
     */
    public function getById($discountId)
    {
        return Discount::find($discountId);
    }

    public function updateProductPrice(Product $product, $newPrice)
    {
        $product->update(['price' => $newPrice]);
    }

    /**
     * Crée une nouvelle remise.
     *
     * @param  array  $data
     * @return \App\Models\Discount
     */
    public function create(array $data)
    {
        return Discount::create($data);
    }

    /**
     * Met à jour une remise existante.
     *
     * @param  int  $discountId
     * @param  array  $data
     * @return bool
     */
    public function update($discountId, array $data)
    {
        $discount = Discount::find($discountId);

        if ($discount) {
            return $discount->update($data);
        }

        return false;
    }

    /**
     * Supprime une remise.
     *
     * @param  int  $discountId
     * @return bool|null
     */
    public function delete($discountId)
    {
        $discount = Discount::find($discountId);

        if ($discount) {
            return $discount->delete();
        }

        return false;
    }

    /**
     * Récupère toutes les remises.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Discount::all();
    }
}
