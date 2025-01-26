<?php

namespace App\Services\DiscountRules;

use App\Models\Order;
use App\Services\Discount\DiscountRuleInterface;
use Illuminate\Support\Facades\Cache;

class DiscountService
{
    protected $rules;

    public function __construct(array $rules)
    {
        $this->rules = $rules;
    }

    public function calculateDiscounts(Order $order): array
    {
        // Cache anahtarını oluştur (örneğin, sipariş ID'si kullanılabilir)
        $cacheKey = 'order_discounts_' . $order->id;

        // Cache'ten veriyi al
        $cachedResult = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedResult) {
            return $cachedResult;
        }

        // Cache'te veri yoksa, indirimleri hesapla
        $discounts = [];
        $totalDiscount = 0;
        $subtotal = $order->total;

        foreach ($this->rules as $rule) {
            $ruleDiscounts = $rule->calculateDiscount($order, $subtotal);
            foreach ($ruleDiscounts as $discount) {
                $discounts[] = $discount;
                $totalDiscount += (float) $discount['discountAmount'];
                $subtotal -= (float) $discount['discountAmount'];
            }
        }

        $result = [
            'orderId' => $order->id,
            'discounts' => $discounts,
            'totalDiscount' => number_format($totalDiscount, 2, '.', ''),
            'discountedTotal' => number_format($subtotal, 2, '.', '')
        ];

        // Sonucu cache'e kaydet (örneğin, 60 dakika boyunca cache'te tut)
        Cache::put($cacheKey, $result, now()->addMinutes(60));

        return $result;
    }
}