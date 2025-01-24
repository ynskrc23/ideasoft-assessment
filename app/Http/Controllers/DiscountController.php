<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function calculateDiscounts(Order $order)
    {
        $customer = Customer::find($order->customer_id);
        $discounts = [];
        $totalDiscount = 0;
        $subtotal = $order->total;

        // Rule 1: 10% discount for orders over 1000 TL
        if ($order->total >= 1000) {
            $discountAmount = $order->total * 0.10;
            $discounts[] = [
                'discountReason' => '10_PERCENT_OVER_1000',
                'discountAmount' => number_format($discountAmount, 2),
                'subtotal' => number_format($order->total - $discountAmount, 2)
            ];
            $totalDiscount += $discountAmount;
            $subtotal -= $discountAmount;
        }

        // Rule 2: Buy 5 get 1 free for category 2
        foreach ($order->items as $item) {
            $product = Product::find($item['product_id']);
            if ($product->category == 2 && $item['quantity'] >= 6) {
                $freeItems = intdiv($item['quantity'], 6);
                $discountAmount = $freeItems * $item['unit_price'];
                $discounts[] = [
                    'discountReason' => 'BUY_5_GET_1',
                    'discountAmount' => number_format($discountAmount, 2),
                    'subtotal' => number_format($subtotal - $discountAmount, 2)
                ];
                $totalDiscount += $discountAmount;
                $subtotal -= $discountAmount;
            }
        }

        // Rule 3: 20% discount on the cheapest item for category 1
        $category1Items = array_filter($order->items, function ($item) {
            $product = Product::find($item['product_id']);
            return $product->category == 1;
        });

        if (count($category1Items) >= 2) {
            $cheapestItem = min(array_column($category1Items, 'unit_price'));
            $discountAmount = $cheapestItem * 0.20;
            $discounts[] = [
                'discountReason' => '20_PERCENT_CHEAPEST_CATEGORY_1',
                'discountAmount' => number_format($discountAmount, 2),
                'subtotal' => number_format($subtotal - $discountAmount, 2)
            ];
            $totalDiscount += $discountAmount;
            $subtotal -= $discountAmount;
        }

        return response()->json([
            'orderId' => $order->id,
            'discounts' => $discounts,
            'totalDiscount' => number_format($totalDiscount, 2),
            'discountedTotal' => number_format($subtotal, 2)
        ]);
    }
}