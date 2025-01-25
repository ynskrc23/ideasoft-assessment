<?php

namespace App\Services\DiscountRules;

use App\Models\Order;

class TenPercentOverThousandRule implements DiscountRuleInterface
{
    public function calculateDiscount(Order $order, float $subtotal): array
    {
        $discounts = [];
        if ($subtotal >= 1000) {
            $discountAmount = $subtotal * 0.10;
            $discounts[] = [
                'discountReason' => '10_PERCENT_OVER_1000',
                'discountAmount' => number_format($discountAmount, 2),
                'subtotal' => number_format($subtotal - $discountAmount, 2)
            ];
        }
        return $discounts;
    }
}