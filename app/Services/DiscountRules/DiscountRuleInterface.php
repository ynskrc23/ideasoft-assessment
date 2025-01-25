<?php

namespace App\Services\DiscountRules;

use App\Models\Order;

interface DiscountRuleInterface
{
    public function calculateDiscount(Order $order, float $subtotal): array;
}