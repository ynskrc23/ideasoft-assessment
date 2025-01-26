<?php

namespace App\Services\DiscountRules;

use App\Models\Order;
use App\Models\Product;

class BuyFiveGetOneFreeRule implements DiscountRuleInterface
{
    public function calculateDiscount(Order $order, float $subtotal): array
    {
        $discounts = [];
        foreach ($order->items as $item) {
            $product = Product::find($item['productId']);
            if ($product->category == 2 && $item['quantity'] >= 6) {
                $freeItems = intdiv($item['quantity'], 6);
                $discountAmount = $freeItems * $item['unitPrice'];
                $discounts[] = [
                    'discountReason' => 'BUY_5_GET_1',
                    'discountAmount' => number_format($discountAmount, 2, '.', ''),
                    'subtotal' => number_format($subtotal - $discountAmount, 2, '.', '')
                ];
            }
        }
        return $discounts;
    }
}