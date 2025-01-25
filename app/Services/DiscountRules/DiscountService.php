<?php

namespace App\Services\DiscountRules;

use App\Models\Order;
use App\Services\DiscountRules\DiscountRuleInterface;

class DiscountService
{
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function calculateDiscounts(Order $order): array
    {
        $discounts = [];
        $totalDiscount = 0;
        $subtotal = $order->total;

        // Sıralanmış items üzerinde indirim kurallarını uygula
        foreach ($this->rules as $rule) {
            $ruleDiscounts = $rule->calculateDiscount($order, $subtotal);
            foreach ($ruleDiscounts as $discount) {
                $discounts[] = $discount;
                $totalDiscount += (float) $discount['discountAmount'];
                $subtotal -= (float) $discount['discountAmount'];
            }
        }

        return [
            'orderId' => $order->id,
            'discounts' => $discounts,
            'totalDiscount' => number_format($totalDiscount, 2),
            'discountedTotal' => number_format($subtotal, 2)
        ];
    }
}