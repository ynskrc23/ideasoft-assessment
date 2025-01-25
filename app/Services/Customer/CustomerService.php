<?php 

namespace App\Services\Customer;

use App\Repositories\Customer\CustomerRepositoryInterface;
use App\Exceptions\CustomerNotFoundException;

class CustomerService implements CustomerServiceInterface
{
    protected $customerRepository;

    public function __construct(CustomerRepositoryInterface $customerRepository)
    {
        $this->customerRepository = $customerRepository;
    }

    public function getAllCustomers()
    {
        return $this->customerRepository->all();
    }

    public function getCustomerById($id)
    {
        $customer = $this->customerRepository->find($id);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }
        return $customer;
    }

    public function createCustomer(array $data)
    {
        return $this->customerRepository->create($data);
    }

    public function updateCustomer($id, array $data)
    {
        $customer = $this->customerRepository->update($id, $data);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }
        return $customer;
    }

    public function deleteCustomer($id)
    {
        $customer = $this->customerRepository->delete($id);
        if (!$customer) {
            throw new CustomerNotFoundException('Müşteri bulunamadı.', $id);
        }
        return $customer;
    }
}