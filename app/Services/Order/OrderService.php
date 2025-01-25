<?php 

namespace App\Services\Order;

use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\InsufficientStockException;

class OrderService implements OrderServiceInterface
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(OrderRepositoryInterface $orderRepository,ProductRepositoryInterface $productRepository) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function getAllOrders()
    {
        return $this->orderRepository->all();
    }

    public function getOrderById($id)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new OrderNotFoundException('Sipariş bulunamadı.', $id);
        }
        return $order;
    }

    public function createOrder(array $data)
    {
        // Stok kontrolü
        foreach ($data['items'] as $item) {
            $product = $this->productRepository->find($item['productId']);
            if (!$product) {
                throw new ProductNotFoundException('Ürün bulunamadı.', $item['productId']);
            }
            if ($product->stock < $item['quantity']) {
                throw new InsufficientStockException('Yetersiz stok.', $item['productId']);
            }
        }

        // Sipariş oluştur
        $order = $this->orderRepository->create($data);

        // Stok güncelleme
        foreach ($data['items'] as $item) {
            $this->productRepository->updateStock($item['productId'], $item['quantity']);
        }

        return $order;
    }

    public function deleteOrder($id)
    {
        $order = $this->orderRepository->delete($id);
        if (!$order) {
            throw new OrderNotFoundException('Sipariş bulunamadı.', $id);
        }
        return $order;
    }
}