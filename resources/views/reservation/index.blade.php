<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-300 dark:text-gray-200 leading-tight">
            Reservations
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages></x-messages>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($reservations as $reservation)
                    <div
                        class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <a href="{{ route('movies.show', $reservation->movie) }}">
                            <img src="{{ url('storage/'.$reservation->movie->thumbnail) }}"
                                 alt="{{ $reservation->movie->name }}" class="w-full h-48 object-cover">
                        </a>
                        <div class="p-4">
                            <h2 class="text-2xl my-2 font-bold  dark:text-indigo-400">
                                <strong>{{ $reservation->movie->name }}</strong></h2>
                            <p class="my-2 text-sm font-bold dark:text-gray-200">Showing
                                Date: {{ $reservation->movie->schedule->showing_date }}</p>
                            <p class="my-2 text-sm font-bold dark:text-gray-200">
                                Time: {{ \Carbon\Carbon::parse($reservation->movie->schedule->start_time)->format('h:i A') }}
                            </p>
                            <span
                                class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($reservation->movie->schedule['start_time'])->diff(\Carbon\Carbon::parse($reservation->movie->schedule['end_time']))->format('%H:%I') }}
                            hours
                            </span>
                            <p class="my-2 text-sm dark:text-gray-400">Standard Seats: {{ $reservation->standard_seats }} - ({{ $reservation->movie->standard_price }}$)</p>
                            <p class="my-2 text-sm dark:text-gray-400">VIP Seats: {{ $reservation->vip_seats }} - ({{ $reservation->movie->vip_price }}$)</p>
                            <p class="my-2 text-sm dark:text-gray-400">
                                Hall: {{ $reservation->movie->hall->name }}
                            </p>
                            <p class="my-2 text-sm dark:text-gray-400">Total:
                                {{ $reservation->total_price }} $
                            </p>
                            <a href="{{ route('reservations.download', $reservation->id) }}" class="dark:text-gray-200 dark:bg-indigo-500 rounded hover:dark:bg-indigo-600 px-2 py-1">
                                Download Reservation
                            </a>
                            @if(count($reservation->consumables) > 0)
                                <p class="my-2 text-sm font-bold dark:text-gray-200">Consumables:</p>
                                <ul class="list-disc pl-5">
                                    @foreach($reservation->consumables as $consumable)
                                        <li class="text-sm dark:text-gray-400">{{ $consumable->name }}
                                            (x{{ $consumable->pivot->quantity }})
                                            - {{ $consumable->pivot->price * $consumable->pivot->quantity }} $
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="my-2 text-sm dark:text-gray-400">No consumables selected</p>
                            @endif

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>

