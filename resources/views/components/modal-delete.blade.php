@props(['modalId' => \Illuminate\Support\Str::uuid(), 'route' => ''])

<a href="javascript:;"
   class="font-medium text-red-600 dark:text-red-500 hover:underline"
   x-data=""
   x-on:click.prevent="$dispatch('open-modal', '{{ $modalId }}')">
    {{ __('Delete')  }}
</a>

<x-modal id="{{ $modalId }}" name="{{ $modalId }}" :show="$errors->customer->isNotEmpty()" focusable>
    <form method="post" action="{{ $route }}" class="p-6">
        @csrf
        @method('delete')

        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Are you sure you want to delete this record?') }}
        </h2>

        <div class="mt-6 flex justify-end">
            <x-secondary-button x-on:click="$dispatch('close')">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3">
                {{ __('Delete') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
