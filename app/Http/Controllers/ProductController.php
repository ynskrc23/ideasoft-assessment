<?php

namespace App\Http\Controllers;

use App\Services\Product\ProductServiceInterface;
use App\Exceptions\ProductNotFoundException;
use Illuminate\Http\Request;

class ProductController extends BaseController
{
    protected $productService;

    public function __construct(ProductServiceInterface $productService)
    {
        $this->productService = $productService;
    }

    // Tüm ürünleri listeleme
    public function index()
    {
        try {
            $products = $this->productService->getAllProducts();
            return response()->json($products);
        } catch (\Exception $e) {
            return $this->jsonError(
                'Ürün getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Belirli bir ürünü görüntüleme
    public function show($id)
    {
        try {
            $product = $this->productService->getProductById($id);
            return response()->json($product);
        } catch (ProductNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Ürün getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Yeni ürün ekleme
    public function store(Request $request)
    {
        try {
            $data = $this->validateRequest($request, [
                'name' => 'required|string|max:255',
                'category' => 'required|integer',
                'price' => 'required|numeric',
                'stock' => 'required|integer',
            ]);

            $product = $this->productService->createProduct($data);
            return response()->json($product, 201);
        } catch (\Exception $e) {
            return $this->jsonError(
                'Ürün oluşturulurken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Ürün bilgilerini güncelleme
    public function update(Request $request, $id)
    {
        try {
            $data = $this->validateRequest($request, [
                'name' => 'sometimes|string|max:255',
                'category' => 'sometimes|integer',
                'price' => 'sometimes|numeric',
                'stock' => 'sometimes|integer',
            ]);

            $product = $this->productService->updateProduct($id, $data);
            return response()->json($product);
        } catch (ProductNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Ürün güncellenirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Ürün silme
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->noContent();
        } catch (ProductNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Ürün silinirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }
}