<?php 

namespace App\Services\Customer;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Exceptions\CustomerNotFoundException;
use Illuminate\Support\Facades\Cache;

class CustomerService implements CustomerServiceInterface
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers()
    {
        $cacheKey = 'all_customers';

        // Cache'ten veriyi al
        $cachedCustomers = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedCustomers) {
            return $cachedCustomers;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $customers = $this->customerRepository->all();
        Cache::put($cacheKey, $customers, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $customers;
    }

    public function getCustomerById($id)
    {
        $cacheKey = 'customer_' . $id;

        // Cache'ten veriyi al
        $cachedCustomer = Cache::get($cacheKey);

        // Eğer cache'te veri varsa, cache'ten döndür
        if ($cachedCustomer) {
            return $cachedCustomer;
        }

        // Cache'te veri yoksa, veritabanından çek ve cache'e kaydet
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }

        Cache::put($cacheKey, $customer, now()->addMinutes(60)); // 60 dakika boyunca cache'te tut

        return $customer;
    }

    public function createCustomer(array $data)
    {
        // Yeni müşteri oluştur
        $customer = $this->customerRepository->create($data);

        // Tüm müşterilerin cache'ini temizle (yeni müşteri eklendiği için)
        Cache::forget('all_customers');

        return $customer;
    }

    public function updateCustomer($id, array $data)
    {
        // Müşteriyi güncelle
        $customer = $this->customerRepository->update($id, $data);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }

        // Güncellenen müşterinin cache'ini temizle
        $cacheKey = 'customer_' . $id;
        Cache::forget($cacheKey);

        // Tüm müşterilerin cache'ini temizle (müşteri güncellendiği için)
        Cache::forget('all_customers');

        return $customer;
    }

    public function deleteCustomer($id)
    {
        // Müşteriyi sil
        $customer = $this->customerRepository->delete($id);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }

        // Silinen müşterinin cache'ini temizle
        $cacheKey = 'customer_' . $id;
        Cache::forget($cacheKey);

        // Tüm müşterilerin cache'ini temizle (müşteri silindiği için)
        Cache::forget('all_customers');

        return $customer;
    }
}