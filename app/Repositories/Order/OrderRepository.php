<?php

namespace App\Repositories\Order;

use App\Models\Order;

class OrderRepository implements OrderRepositoryInterface
{
    public function all()
    {
        return Order::all();
    }

    public function find($id)
    {
        return Order::find($id);
    }

    public function create(array $data)
    {
        return Order::create($data);
    }

    public function delete($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
        }
        return $order;
    }
}