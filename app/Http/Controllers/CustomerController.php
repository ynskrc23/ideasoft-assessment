<?php

namespace App\Http\Controllers;

use App\Services\Customer\CustomerServiceInterface;
use App\Exceptions\CustomerNotFoundException;
use Illuminate\Http\Request;

class CustomerController extends BaseController
{
    protected $customerService;

    public function __construct(CustomerServiceInterface $customerService)
    {
        $this->customerService = $customerService;
    }

    // Tüm müşterileri listeleme
    public function index()
    {
        try {
            $customers = $this->customerService->getAllCustomers();
            return response()->json($customers);
        } catch (\Exception $e) {
            return $this->jsonError(
                'Müşteri getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Belirli bir müşteriyi görüntüleme
    public function show($id)
    {
        try {
            $customer = $this->customerService->getCustomerById($id);
            return response()->json($customer);
        } catch (CustomerNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Müşteri getirilirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Yeni müşteri ekleme
    public function store(Request $request)
    {
        try {
            $data = $this->validateRequest($request, [
                'name' => 'required|string|max:255',
                'since' => 'required|date',
                'revenue' => 'required|numeric',
            ]);

            $customer = $this->customerService->createCustomer($data);
            return response()->json($customer, 201);
        } catch (\Exception $e) {
            return $this->jsonError(
                'Müşteri oluşturulurken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Müşteri bilgilerini güncelleme
    public function update(Request $request, $id)
    {
        try {
            $data = $this->validateRequest($request, [
                'name' => 'sometimes|string|max:255',
                'since' => 'sometimes|date',
                'revenue' => 'sometimes|numeric',
            ]);

            $customer = $this->customerService->updateCustomer($id, $data);
            return response()->json($customer);
        } catch (CustomerNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Müşteri güncellenirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }

    // Müşteri silme
    public function destroy($id)
    {
        try {
            $this->customerService->deleteCustomer($id);
            return response()->noContent();
        } catch (CustomerNotFoundException $e) {
            return $this->jsonError(
                $e->getMessage(),
                404,
                ['error_id' => $e->getId()]
            );
        } catch (\Exception $e) {
            return $this->jsonError(
                'Müşteri silinirken bir hata oluştu.',
                500,
                [$e->getMessage()]
            );
        }
    }
}