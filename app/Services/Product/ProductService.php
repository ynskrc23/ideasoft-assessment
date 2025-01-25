<?php

namespace App\Services\Product;

use App\Repositories\Product\ProductRepositoryInterface;
use App\Exceptions\ProductNotFoundException;

class ProductService implements ProductServiceInterface
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function getAllProducts()
    {
        return $this->productRepository->all();
    }

    public function getProductById($id)
    {
        $product = $this->productRepository->find($id);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }
        return $product;
    }

    public function createProduct(array $data)
    {
        return $this->productRepository->create($data);
    }

    public function updateProduct($id, array $data)
    {
        $product = $this->productRepository->update($id, $data);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }
        return $product;
    }

    public function deleteProduct($id)
    {
        $product = $this->productRepository->delete($id);
        if (!$product) {
            throw new ProductNotFoundException('Ürün bulunamadı.', $id);
        }
        return $product;
    }
}