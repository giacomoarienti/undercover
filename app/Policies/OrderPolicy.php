<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class OrderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        /**
         * $user is already non-null at this point
         */
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Order $order): bool
    {
        return $order->user_id === $user->id //l'ordine è dell'utente
            or (
                //uno dei prodotti nell'ordine appartiene all'utente
                $order->specificProducts->contains(function ($specificProduct) use ($user) {
                    $specificProduct->product->user_id === $user->id;
                })
            );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->is_seller and $user->cart_count > 0;
    }
}
