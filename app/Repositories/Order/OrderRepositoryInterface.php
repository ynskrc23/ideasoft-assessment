<?php

namespace App\Repositories\Order;

interface OrderRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function delete($id);
}