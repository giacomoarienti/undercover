<?php

namespace App\Policies;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PaymentMethodPolicy
{
    public function use(User $user, PaymentMethod $paymentMethod): bool {
        return $paymentMethod->user->id == $user->id;
    }
}
