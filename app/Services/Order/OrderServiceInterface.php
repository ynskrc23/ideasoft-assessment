<?php

namespace App\Services\Order;

interface OrderServiceInterface
{
    public function getAllOrders();
    public function getOrderById($id);
    public function createOrder(array $data);
    public function deleteOrder($id);
}