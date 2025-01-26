<?php

namespace App\Services\DiscountRules;

use App\Models\Order;
use App\Models\Product;

class TwentyPercentCheapestCategoryOneRule implements DiscountRuleInterface
{
    public function calculateDiscount(Order $order, float $subtotal): array
    {
        $discounts = [];
        $category1Items = array_filter($order->items, function ($item) {
            $product = Product::find($item['productId']);
            return $product->category == 1;
        });

        if (count($category1Items) >= 2) {
            $cheapestItem = min(array_column($category1Items, 'unitPrice'));
            $discountAmount = $cheapestItem * 0.20;
            $discounts[] = [
                'discountReason' => '20_PERCENT_CHEAPEST_CATEGORY_1',
                'discountAmount' => number_format($discountAmount, 2, '.', ''),
                'subtotal' => number_format($subtotal - $discountAmount, 2, '.', '')
            ];
        }
        return $discounts;
    }
}