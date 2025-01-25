<?php

namespace App\Enums;

enum OrderStatus: string
{
    case AWAITING_PAYMENT = 'awaiting payment';
    case PENDING = 'pending';
    case SHIPPED = 'shipped';
    case DELIVERED = 'delivered';
}
