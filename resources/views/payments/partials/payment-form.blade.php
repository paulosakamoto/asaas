<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            @if($payment->exists)
                {{ __('Edit payment') }} #{{ $payment->id  }}
            @else
                {{ __('New payment') }}
            @endif
        </h2>
    </header>

    @if (empty($customers))
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <a href="{{ route('customers.create') }}" class="hover:underline">
                    {{ __('It is required to create a customer') }}
                </a>
            </div>
        </div>
    @else
        <form method="post" action="{{ $payment->exists ? route('payments.update', ['payment' => $payment->id]) : route('payments.store') }}" class="mt-6 space-y-6" x-data="{ billingType: '{{ old('billing_type', $payment->billing_type)  }}' }">
            @csrf
            @if($payment->exists)
                @method('put')
            @else
                @method('post')
            @endif

            <div>
                <x-input-label for="payment_customer_id" :value="__('Customer')" />
                <x-input-select id="payment_customer_id" name="customer_id" :options="$customers" value="{{ old('customer_id', $payment->customer_id) }}" class="mt-1 block w-full"></x-input-select>
                <x-input-error :messages="$errors->payment->get('customer_id')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="payment_due_date" :value="__('Due Date')" />
                <x-text-input id="payment_due_date" name="due_date" type="date" class="mt-1 block w-full" value="{{ old('due_date', $payment->dueDateFormatted()) }}" />
                <x-input-error :messages="$errors->payment->get('due_date')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="payment_value" :value="__('Value')" />
                <x-text-input id="payment_value" name="value" type="text" class="mt-1 block w-full" value="{{ old('value', $payment->value) }}" />
                <x-input-error :messages="$errors->payment->get('value')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="payment_billing_type" :value="__('Billing Type')" />
                <x-input-select x-model="billingType" id="payment_billing_type" name="billing_type" :options="$billingTypes" value="{{ old('billing_type', $payment->billing_type) }}" class="mt-1 block w-full"></x-input-select>
                <x-input-error :messages="$errors->payment->get('billing_type')" class="mt-2" />
            </div>

            <fieldset x-show="billingType == '{{ \App\Enums\BillingType::CREDIT_CARD->value }}'">
                <legend class="text-white mt-1 mb-2 block w-full">{{ __('Credit Card Information') }}</legend>

                <div>
                    <x-input-label for="credit_card_holder_name" :value="__('Holder Name')" />
                    <x-text-input id="credit_card_holder_name" name="credit_card[holder_name]" type="text" class="mt-1 block w-full" value="{{ old('credit_card.holder_name') }}" />
                    <x-input-error :messages="$errors->payment->get('credit_card.holder_name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="credit_card_number" :value="__('Number')" />
                    <x-text-input id="credit_card_number" name="credit_card[number]" type="text" class="mt-1 block w-full" value="{{ old('credit_card.number') }}" />
                    <x-input-error :messages="$errors->payment->get('credit_card.number')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="credit_card_expiry_month" :value="__('Expiry Month')" />
                    <x-text-input id="credit_card_expiry_month" name="credit_card[expiry_month]" type="text" class="mt-1 block w-full" value="{{ old('credit_card.expiry_month') }}" />
                    <x-input-error :messages="$errors->payment->get('credit_card.expiry_month')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="credit_card_expiry_year" :value="__('Expiry Year')" />
                    <x-text-input id="credit_card_expiry_year" name="credit_card[expiry_year]" type="text" class="mt-1 block w-full" value="{{ old('credit_card.expiry_year') }}" />
                    <x-input-error :messages="$errors->payment->get('credit_card.expiry_year')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="credit_card_ccv" :value="__('CCV')" />
                    <x-text-input id="credit_card_ccv" name="credit_card[ccv]" type="text" class="mt-1 block w-full" value="{{ old('credit_card.ccv') }}" />
                    <x-input-error :messages="$errors->payment->get('credit_card.ccv')" class="mt-2" />
                </div>
            </fieldset>

            <fieldset x-show="billingType == '{{ \App\Enums\BillingType::CREDIT_CARD->value }}'">
                <legend class="text-white mt-1 mb-2 block w-full">{{ __('Holder Information') }}</legend>

                <div>
                    <x-input-label for="holder_name" :value="__('Name')" />
                    <x-text-input id="holder_name" name="holder[name]" type="text" class="mt-1 block w-full" value="{{ old('holder.name') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.name')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_email" :value="__('Email')" />
                    <x-text-input id="holder_email" name="holder[email]" type="email" class="mt-1 block w-full" value="{{ old('holder.email') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.email')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_cpf_cnpj" :value="__('CPF/CNPJ')" />
                    <x-text-input id="holder_cpf_cnpj" name="holder[cpf_cnpj]" type="text" class="mt-1 block w-full" value="{{ old('holder.cpf_cnpj') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.cpf_cnpj')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_postal_code" :value="__('Postal Code')" />
                    <x-text-input id="holder_postal_code" name="holder[postal_code]" type="text" class="mt-1 block w-full" value="{{ old('holder.postal_code') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.postal_code')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_address_number" :value="__('Address Number')" />
                    <x-text-input id="holder_address_number" name="holder[address_number]" type="text" class="mt-1 block w-full" value="{{ old('holder.address_number') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.address_number')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_address_complement" :value="__('Address Complement')" />
                    <x-text-input id="holder_address_complement" name="holder[address_complement]" type="text" class="mt-1 block w-full" value="{{ old('holder.address_complement') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.address_complement')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_phone" :value="__('Phone')" />
                    <x-text-input id="holder_phone" name="holder[phone]" type="text" class="mt-1 block w-full" value="{{ old('holder.phone') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.phone')" class="mt-2" />
                </div>

                <div>
                    <x-input-label for="holder_mobile_phone" :value="__('Mobile Phone')" />
                    <x-text-input id="holder_mobile_phone" name="holder[mobile_phone]" type="text" class="mt-1 block w-full" value="{{ old('holder.mobile_phone') }}" />
                    <x-input-error :messages="$errors->payment->get('holder.mobile_phone')" class="mt-2" />
                </div>

            </fieldset>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>

                @if (session('status') === 'payment-saved')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 2000)"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    @endif

</section>
