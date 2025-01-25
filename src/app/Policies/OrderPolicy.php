<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use function Laravel\Prompts\error;

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
        return $order->user_id === $user->id || //l'ordine è dell'utente
            // almeno uno dei prodotti nell'ordine appartiene all'utente
            in_array(
                true,
                $order->specificProducts->map(fn($specificProduct) =>
                    $specificProduct->product->user_id === $user->id)->toArray()
            );
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return !$user->is_seller and $user->cart->count() > 0;
    }
}
