<?php

namespace App\Http\Controllers;

use App\Api\Asaas\Payments;
use App\Enums\BillingType;
use App\Http\Requests\PaymentRequest;
use App\Models\Customer;
use App\Models\Payment;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 *
 */
class PaymentsController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('payments.index', [
            'payments' => Payment::all()
        ]);
    }

    /**
     * @return View
     */
    public function create(): View
    {
        $payment = new Payment;
        $payment->billint_type = BillingType::PIX->value; //default
        return view('payments.create', [
            'payment' => $payment,
            'billingTypes' => Payment::availableBillingTypes(),
            'customers' => Customer::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * @param Payment $customer
     * @return View
     */
    public function edit(Payment $payment): View
    {
        return view('payments.edit', [
            'payment' => $payment,
            'billingTypes' => Payment::availableBillingTypes(),
            'customers' => Customer::all()->pluck('name', 'id')->toArray()
        ]);
    }

    /**
     * @param PaymentRequest $request
     * @param Payments $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function store(PaymentRequest $request, Payments $apiClient): RedirectResponse
    {
        $payment = new Payment;
        $payment->fill($request->validated())->save();
        $asaasObject = $apiClient->create($apiClient->mapFromModel($payment));
        $payment->fill($apiClient->mapToModel($asaasObject))->save();
        return Redirect::route('payments.edit', ['payment' => $payment])->with('status', 'payment-saved');
    }

    /**
     * @param PaymentRequest $request
     * @param Payment $payment
     * @param Payments $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function update(PaymentRequest $request, Payment $payment, Payments $apiClient): RedirectResponse
    {
        $payment->fill($request->validated());
        $asaasObject = $apiClient->update($payment->getAsaasId(), $apiClient->mapFromModel($payment));
        $payment->fill($apiClient->mapToModel($asaasObject))->save();
        return Redirect::route('payments.edit', ['payment' => $payment])->with('status', 'payment-saved');
    }

    /**
     * @param Payment $payment
     * @param Payments $apiClient
     * @return RedirectResponse
     * @throws RequestException
     */
    public function destroy(Payment $payment, Payments $apiClient): RedirectResponse
    {
        $payment->delete();
        $apiClient->delete($payment->getAsaasId());
        return Redirect::route('payments.index')->with('status', 'payment-deleted');
    }
}
