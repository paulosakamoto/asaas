<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            @if($customer->exists)
                {{ __('Edit customer') }} #{{ $customer->id  }}
            @else
                {{ __('New customer') }}
            @endif
        </h2>
    </header>

    <form method="post" action="{{ $customer->exists ? route('customers.update', ['customer' => $customer->id]) : route('customers.store') }}" class="mt-6 space-y-6" x-data="{ personType: '{{ old('person_type', $customer->person_type)  }}' }">
        @csrf
        @if($customer->exists)
            @method('put')
        @else
            @method('post')
        @endif

        <div>
            <x-input-label for="customers_name" :value="__('Name')" />
            <x-text-input id="customers_name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name', $customer->name) }}" />
            <x-input-error :messages="$errors->customer->get('name')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="customers_email" :value="__('Email')" />
            <x-text-input id="customers_email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $customer->email) }}" />
            <x-input-error :messages="$errors->customer->get('email')" class="mt-2" />
        </div>

        @if($customer->exists)
            <div>
                <x-input-label for="customers_person_type" :value="__('Person Type')" />
                <x-text-input id="customers_person_type" name="person_type" type="text" class="mt-1 block w-full" readonly value="{{ old('person_type', $customer->person_type) }}" />
            </div>
            @if($customer->fisica())
                <div>
                    <x-input-label for="customers_cpf" :value="__('CPF')" />
                    <x-text-input id="customers_cpf" name="cpf" type="text" class="mt-1 block w-full" value="{{ old('cpf', $customer->cpf_cnpj) }}" />
                    <x-input-error :messages="$errors->customer->get('cpf')" class="mt-2" />
                </div>
            @else
                <div>
                    <x-input-label for="customers_cnpj" :value="__('CNPJ')" />
                    <x-text-input id="customers_cnpj" name="cnpj" type="text" class="mt-1 block w-full" value="{{ old('cnpj', $customer->cpf_cnpj) }}" />
                    <x-input-error :messages="$errors->customer->get('cnpj')" class="mt-2" />
                </div>
            @endif
        @else

            <div>
                <x-input-label for="customers_person_type" :value="__('Person Type')" />
                <x-input-select x-model="personType" id="customers_person_type" name="person_type" :options="$personTypes" value="{{ old('person_type', $customer->person_type) }}" class="mt-1 block w-full"></x-input-select>
                <x-input-error :messages="$errors->customer->get('person_type')" class="mt-2" />
            </div>

            <div x-show="personType == '{{ \App\Enums\PersonType::FISICA->value }}'">
                <x-input-label for="customers_cpf" :value="__('CPF')" />
                <x-text-input id="customers_cpf" name="cpf" type="text" class="mt-1 block w-full" value="{{ old('cpf', $customer->cpf_cnpj) }}" />
                <x-input-error :messages="$errors->customer->get('cpf')" class="mt-2" />
            </div>

            <div x-show="personType == '{{ \App\Enums\PersonType::JURIDICA->value }}'">
                <x-input-label for="customers_cnpj" :value="__('CNPJ')" />
                <x-text-input id="customers_cnpj" name="cnpj" type="text" class="mt-1 block w-full" value="{{ old('cnpj', $customer->cpf_cnpj) }}" />
                <x-input-error :messages="$errors->customer->get('cnpj')" class="mt-2" />
            </div>

        @endif

        <div>
            <x-input-label for="customers_mobile_phone" :value="__('Mobile Phone')" />
            <x-text-input id="customers_mobile_phone" name="mobile_phone" type="text" class="mt-1 block w-full" value="{{ old('mobile_phone', $customer->mobile_phone) }}" />
            <x-input-error :messages="$errors->customer->get('mobile_phone')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'customer-saved')
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
</section>
