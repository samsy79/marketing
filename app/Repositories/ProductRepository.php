<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Collection;

class ProductRepository
{
    /**
     * Récupère un produit par son ID.
     *
     * @param  int  $productId
     * @return \App\Models\Product|null
     */
    public function getById($productId)
    {
        return Product::find($productId);
    }

    public function getProductsByIds($productIds)
    {
        return Product::whereIn('id', $productIds)->get();
    }
    /**
     * Crée un nouveau produit.
     *
     * @param  array  $data
     * @return \App\Models\Product
     */
    public function create(array $data)
    {
        return Product::create($data);
    }

    /**
     * Met à jour un produit existant.
     *
     * @param  int  $productId
     * @param  array  $data
     * @return bool
     */
    public function update($productId, array $data)
    {
        $product = Product::find($productId);

        if ($product) {
            return $product->update($data);
        }

        return false;
    }

    /**
     * Supprime un produit.
     *
     * @param  int  $productId
     * @return bool|null
     */
    public function delete($productId)
    {
        $product = Product::find($productId);

        if ($product) {
            return $product->delete();
        }

        return false;
    }

    /**
     * Récupère tous les produits.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Product::all();
    }
}
