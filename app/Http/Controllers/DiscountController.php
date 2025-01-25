<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\DiscountRules\DiscountService;

class DiscountController extends Controller
{
    protected $discountService;

    public function __construct(DiscountService $discountService)
    {
        $this->discountService = $discountService;
    }

    public function calculateDiscounts(Order $order)
    {
        return response()->json($this->discountService->calculateDiscounts($order));
    }
}