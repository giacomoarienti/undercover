<?php

namespace App\Policies;

use App\Models\Product;
use App\Models\SpecificProduct;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpecificProductPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SpecificProduct $specificProduct): bool
    {
        return $user->can('view', $specificProduct->product);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create', Product::class);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SpecificProduct $specificProduct): bool
    {
        return $user->can('update', $specificProduct->product);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SpecificProduct $specificProduct): bool
    {
        return $user->can('delete', $specificProduct->product);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SpecificProduct $specificProduct): bool
    {
        return $user->can('restore', $specificProduct->product);
    }

    public function review(User $user, SpecificProduct $specificProduct): bool
    {
        return false;
    }
}
