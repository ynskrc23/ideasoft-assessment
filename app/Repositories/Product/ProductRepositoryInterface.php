<?php 

namespace App\Repositories\Product;

interface ProductRepositoryInterface
{
    public function all();
    public function find($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function updateStock($id, $quantity);
}