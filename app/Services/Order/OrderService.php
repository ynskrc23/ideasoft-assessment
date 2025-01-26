<?php 

namespace App\Services\Order;

use App\Repositories\Order\OrderRepositoryInterface;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\InsufficientStockException;
use Illuminate\Support\Facades\Cache;

class OrderService implements OrderServiceInterface
{
    protected $orderRepository;
    protected $productRepository;

    public function __construct(OrderRepositoryInterface $orderRepository, ProductRepositoryInterface $productRepository) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }

    public function getAllOrders()
    {
        $cacheKey = 'all_orders';

        // Cache'ten veriyi al
        $cachedOrders = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedOrders) {
            return $cachedOrders;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $orders = $this->orderRepository->all();
        Cache::put($cacheKey, $orders, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $orders;
    }

    public function getOrderById($id)
    {
        $cacheKey = 'order_' . $id;

        // Cache'ten veriyi al
        $cachedOrder = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedOrder) {
            return $cachedOrder;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $order = $this->orderRepository->find($id);
        if (!$order) {
            throw new OrderNotFoundException('Sipariş bulunamadı.', $id);
        }

        Cache::put($cacheKey, $order, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $order;
    }

    public function createOrder(array $data)
    {
        // Stok kontrolü
        foreach ($data['items'] as $item) {
            $cacheKey = 'product_stock_' . $item['productId'];

            // Cache'ten stok bilgisini al
            $cachedStock = Cache::get($cacheKey);

            if ($cachedStock === null) {
                // Cache'te stok bilgisi yoksa, veritabanından çek
                $product = $this->productRepository->find($item['productId']);
                if (!$product) {
                    throw new ProductNotFoundException('Ürün bulunamadı.', $item['productId']);
                }
                $cachedStock = $product->stock;
                Cache::put($cacheKey, $cachedStock, now()->addMinutes(30)); // 30 dakika boyunca cache'te tut
            }

            if ($cachedStock < $item['quantity']) {
                throw new InsufficientStockException('Yetersiz stok.', $item['productId']);
            }
        }

        // Sipariş oluştur
        $order = $this->orderRepository->create($data);

        // Stok güncelleme
        foreach ($data['items'] as $item) {
            $this->productRepository->updateStock($item['productId'], $item['quantity']);

            // Stok bilgisini cache'ten temizle (güncellendiği için)
            $cacheKey = 'product_stock_' . $item['productId'];
            Cache::forget($cacheKey);
        }

        // Tüm siparişlerin cache'ini temizle (yeni sipariş eklendiği için)
        Cache::forget('all_orders');

        return $order;
    }

    public function deleteOrder($id)
    {
        $order = $this->orderRepository->delete($id);
        if (!$order) {
            throw new OrderNotFoundException('Sipariş bulunamadı.', $id);
        }

        // Silinen siparişin cache'ini temizle
        $cacheKey = 'order_' . $id;
        Cache::forget($cacheKey);

        // Tüm siparişlerin cache'ini temizle (sipariş silindiği için)
        Cache::forget('all_orders');

        return $order;
    }
}