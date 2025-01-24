<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    // Tüm müşterileri listeleme
    public function index()
    {
        $customers = Customer::all();
        return response()->json($customers);
    }

    // Yeni müşteri ekleme
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'since' => 'required|date',
            'revenue' => 'required|numeric',
        ]);

        $customer = Customer::create($data);
        return response()->json($customer, 201);
    }

    // Belirli bir müşteriyi görüntüleme
    public function show(Customer $customer)
    {
        return response()->json($customer);
    }

    // Müşteri bilgilerini güncelleme
    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'since' => 'sometimes|date',
            'revenue' => 'sometimes|numeric',
        ]);

        $customer->update($data);
        return response()->json($customer);
    }

    // Müşteri silme
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return response()->noContent();
    }
}