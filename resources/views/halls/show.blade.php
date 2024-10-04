<x-app-layout>
    <x-slot name="header">
        <a class="font-semibold inline text-xl text-gray-400 leading-tight" href="{{ route('halls.index') }}">
            {{ __('Halls') }}
        </a>
        <h2 class="font-semibold inline text-xl text-gray-200 leading-tight">
            / #{{ $hall->id }}
        </h2>
    </x-slot>

    <div class="px-12 mx-auto max-w-7xl">
        <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-8">
            <div class="max-w-xl">
                <section>
                    <header>
                        <h2 class="text-lg font-medium dark:text-gray-100">
                            {{ $hall->name }}
                        </h2>
                        <p class="text-gray-400 text-lg">
                           <strong class="dark:text-gray-100">Standard Seats : </strong>  {{ $hall->standard_seats }}
                        </p>
                        <p class="text-gray-400 text-lg">
                            <strong class="dark:text-gray-100">VIP Seats : </strong>  {{ $hall->standard_seats }}
                        </p>
                    </header>

                </section>
            </div>
        </div>
    </div>
</x-app-layout>
