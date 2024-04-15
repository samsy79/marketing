<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\User;

class CartRepository
{
    /**
     * Récupère un panier par son ID.
     *
     * @param  int  $cartId
     * @return \App\Models\Cart|null
     */
    public function getById($cartId)
    {
        return Cart::find($cartId);
    }
    public function getAbandonedCart(User $user)
    {
        return Cart::where('user_id', $user->id)
                   ->where('is_abandoned', true)
                   ->pluck('product_id', 'quantity')
                   ->toArray();
    }

    /**
     * Crée un nouveau panier.
     *
     * @param  array  $data
     * @return \App\Models\Cart
     */
    public function create(array $data)
    {
        return Cart::create($data);
    }

    /**
     * Met à jour un panier existant.
     *
     * @param  int  $cartId
     * @param  array  $data
     * @return bool
     */
    public function update($cartId, array $data)
    {
        $cart = Cart::find($cartId);

        if ($cart) {
            return $cart->update($data);
        }

        return false;
    }

    /**
     * Supprime un panier.
     *
     * @param  int  $cartId
     * @return bool|null
     */
    public function delete($cartId)
    {
        $cart = Cart::find($cartId);

        if ($cart) {
            return $cart->delete();
        }

        return false;
    }

    /**
     * Récupère tous les paniers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function all()
    {
        return Cart::all();
    }
}
