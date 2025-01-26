<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Support\Facades\Cache;

class ProductService implements ProductServiceInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        $cacheKey = 'all_products';

        // Cache'ten veriyi al
        $cachedProducts = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedProducts) {
            return $cachedProducts;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $products = $this->productRepository->all();
        Cache::put($cacheKey, $products, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $products;
    }

    public function getProductById($id)
    {
        $cacheKey = 'product_' . $id;

        // Cache'ten veriyi al
        $cachedProduct = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedProduct) {
            return $cachedProduct;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }

        Cache::put($cacheKey, $product, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $product;
    }

    public function createProduct(array $data)
    {
        // Yeni ürün oluştur
        $product = $this->productRepository->create($data);

        // Tüm ürünlerin cache'ini temizle (yeni ürün eklendiği için)
        Cache::forget('all_products');

        return $product;
    }

    public function updateProduct($id, array $data)
    {
        // Ürünü güncelle
        $product = $this->productRepository->update($id, $data);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }

        // Güncellenen ürünün cache'ini temizle
        $cacheKey = 'product_' . $id;
        Cache::forget($cacheKey);

        // Tüm ürünlerin cache'ini temizle (ürün güncellendiği için)
        Cache::forget('all_products');

        return $product;
    }

    public function deleteProduct($id)
    {
        // Ürünü sil
        $product = $this->productRepository->delete($id);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }

        // Silinen ürünün cache'ini temizle
        $cacheKey = 'product_' . $id;
        Cache::forget($cacheKey);

        // Tüm ürünlerin cache'ini temizle (ürün silindiği için)
        Cache::forget('all_products');

        return $product;
    }
}