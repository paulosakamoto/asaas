<?php

namespace App\Http\Controllers;

use App\Api\Asaas\Customers;
use App\Enums\PersonType;
use App\Http\Requests\CustomerRequest;
use App\Models\Customer;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 *
 */
class CustomersController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('customers.index', [
            'customers' => Customer::all()
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $customer = new Customer;
        $customer->person_type = PersonType::FISICA->value; //default
        return view('customers.create', [
            'customer' => $customer,
            'personTypes' => PersonType::toArray()
        ]);
    }

    /**
     * @param Customer $customer
     * @return View
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer' => $customer,
            'personTypes' => PersonType::toArray()
        ]);
    }

    /**
     * @param CustomerRequest $request
     * @param Customers $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function store(CustomerRequest $request, Customers $apiClient): RedirectResponse
    {
        $customer = new Customer;
        $customer->fill($request->validated())->setAttribute('cpf_cnpj', $request->input('cpf_cnpj'))->save();
        $data = $apiClient->mapFromModel($customer);
        $asaasObject = $apiClient->create($data);
        $customer->setAsaasId($apiClient->getObjectId($asaasObject));
        $customer->save();
        return Redirect::route('customers.edit', ['customer' => $customer])->with('status', 'customer-saved');
    }

    /**
     * @param CustomerRequest $request
     * @param Customer $customer
     * @param Customers $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function update(CustomerRequest $request, Customer $customer, Customers $apiClient): RedirectResponse
    {
        $customer->fill($request->validated())->setAttribute('cpf_cnpj', $request->input('cpf_cnpj'))->save();
        $apiClient->update($customer->getAsaasId(), $apiClient->mapFromModel($customer));
        return Redirect::route('customers.edit', ['customer' => $customer])->with('status', 'customer-saved');
    }

    /**
     * @param Customer $customer
     * @param Customers $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function destroy(Customer $customer, Customers $apiClient): RedirectResponse
    {
        $customer->delete();
        $apiClient->delete($customer->getAsaasId());
        return Redirect::route('customers.index')->with('status', 'customer-deleted');
    }
}
