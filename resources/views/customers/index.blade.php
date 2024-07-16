<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Customers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="pb-3 text-right">
                <a href="{{ route('customers.create')  }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Add') }}
                </a>
            </div>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                @if ($customers->count())
                    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Id') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Name') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Person Type') }}
                            </th>
                            <th scope="col" class="px-6 py-3">
                                {{ __('Action') }}
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($customers as $customer)
                            <tr class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $customer->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $customer->name }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $customer->person_type }}
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('customers.edit', ['customer' => $customer->id]) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline mr-2">
                                        {{ __('Edit')  }}
                                    </a>

                                    <x-modal-delete route="{{ route('customers.delete', ['customer' => $customer->id]) }}"></x-modal-delete>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __('No records found') }}
                        </div>
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
