<?php

namespace App\Http\Controllers;

use App\Services\Order\OrderServiceInterface;
use App\Exceptions\OrderNotFoundException;
use App\Exceptions\ProductNotFoundException;
use App\Exceptions\InsufficientStockException;
use Illuminate\Http\Request;

class OrderController extends BaseController
{
    protected $orderService;

    public function __construct(OrderServiceInterface $orderService)
    {
        $this->orderService = $orderService;
    }

    // Tüm siparişleri listeleme
    public function index()
    {
        try {
            $orders = $this->orderService->getAllOrders();
            return response()->json($orders);
        } catch (\Exception $e) {
            return $this->jsonError(
                'Sipariş getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Belirli bir siparişi görüntüleme
    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return response()->json($order);
        } catch (OrderNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Sipariş getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Yeni sipariş ekleme
    public function store(Request $request)
    {
        try {
            $data = $this->validateRequest($request, [
                'customerId' => 'required|exists:customers,id',
                'items' => 'required|array',
                'items.*.productId' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.unitPrice' => 'required|numeric',
                'items.*.total' => 'required|numeric',
                'total' => 'required|numeric',
            ]);

            $order = $this->orderService->createOrder($data);
            return response()->json($order, 201);
        } catch (ProductNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['productId' => $e->getId()]
            );
        } catch (InsufficientStockException $e) {
            return $this->jsonError(
                $e->getMessage(),
                400,
                ['productId' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Sipariş oluşturulurken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Sipariş silme
    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder($id);
            return response()->noContent();
        } catch (OrderNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Sipariş silinirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }
}