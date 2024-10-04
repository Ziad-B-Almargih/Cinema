@php use App\Enums\Role;use Carbon\Carbon; @endphp
<x-app-layout>
    <x-slot name="header">
        <div class="flex">
            <h2 class="w-1/2 font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight inline-block">
                <x-back-button></x-back-button>
                Movie Details
            </h2>


            <div class="w-1/2 items-center justify-end inline-flex">
                @if(Auth::user()->role === Role::ADMIN)
                    <a href="{{ route('movies.edit', $movie) }}"
                       class="mx-2 inline-flex items-center px-4 py-2 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 dark:bg-indigo-900 dark:hover:bg-indigo-800">{{ __('Edit') }}</a>
                    <span class="mx-2">
                        <form action="{{ route('movies.destroy', $movie) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <x-secondary-button type="submit"
                                                class="mx-2 dark:bg-red-900 dark:hover:bg-red-800">Delete</x-secondary-button>
                        </form>
                    </span>
                @else
                    <x-primary-button type="button" onclick="openModal()">Book</x-primary-button>
                @endif
            </div>
        </div>

        <!-- Booking Section -->

        <div id="bookingModal"
             class="fixed inset-0 z-50 flex items-center justify-center bg-gray-700 bg-opacity-50 hidden">
            <div class="text-gray-200 dark:bg-gray-800 p-6 rounded-lg shadow-lg max-w-lg w-full">
                <h3 class="text-xl font-semibold mb-2">Book a Reservation</h3>
                <p class="text-sm text-red-600 font-semibold mb-4">Make sure of your reservation, you can't edit or delete it</p>
                <!-- Seat Selection -->
                <div class="mb-4">
                    <label for="standard_seats" class="block font-semibold">Standard Seats ({{ $movie['standard_price'] }}$)</label>
                    <input type="number" id="standard_seats" min="0" max="{{ $movie['empty_standard'] }}"
                           oninput="calculateTotalPrice()"
                           class="w-full border dark:bg-gray-700 dark:text-gray-300 p-2 mt-1"/>
                </div>

                <div class="mb-4">
                    <label for="vip_seats" class="block font-semibold">VIP Seats ({{ $movie['vip_price'] }}$)</label>
                    <input type="number" id="vip_seats" min="0" max="{{ $movie['empty_vip'] }}"
                           oninput="calculateTotalPrice()"
                           class="w-full border dark:bg-gray-700 dark:text-gray-300 p-2 mt-1"/>
                </div>

                <!-- Consumable Selection -->
                <div class="mb-4">
                    <label class="block font-semibold">Consumables</label>
                    @foreach($consumables as $consumable)
                        <div class="flex items-center mb-2">
                            <input type="number" id="consumable_{{ $consumable->id }}" min="0"
                                   oninput="calculateTotalPrice()"
                                   placeholder="{{ __('Qty') }}"
                                   value="0"
                                   class="w-16 border dark:bg-gray-700 dark:text-gray-300 mr-2"/>
                            <span class="dark:text-gray-300">{{ $consumable->name }} ({{ $consumable->price }}$)</span>
                        </div>
                    @endforeach
                </div>

                <!-- Total Price -->
                <div class="mb-4 font-semibold text-lg">
                    Total Price: <span id="totalPrice">0</span>$
                </div>

                <!-- Submit Button -->
                <form action="{{ route('reservations.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="movie_id" value="{{ $movie->id }}"/>
                    <input type="hidden" id="standard_seats_input" name="standard_seats" value="0"/>
                    <input type="hidden" id="vip_seats_input" name="vip_seats" value="0"/>

                    <!-- Nested consumables array -->
                    @foreach($consumables as $consumable)
                        <input type="hidden" name="consumables[{{ $consumable->id }}][id]"
                               value="{{ $consumable->id }}"/>
                        <input type="hidden" id="consumable_quantity_{{ $consumable->id }}"
                               name="consumables[{{ $consumable->id }}][quantity]" value="0"/>
                    @endforeach

                    <x-primary-button type="submit">Submit Reservation</x-primary-button>
                    <x-secondary-button type="button" onclick="closeModal()">Cancel</x-secondary-button>
                </form>
            </div>
        </div>
        <!-- Script -->
        <script>
            function openModal() {
                document.getElementById('bookingModal').classList.remove('hidden');
                document.getElementById('standard_seats').value = 0;
                document.getElementById('vip_seats').value = 0;
            }

            function closeModal() {
                document.getElementById('bookingModal').classList.add('hidden');
            }

            function calculateTotalPrice() {
                const standardPrice = {{ $movie['standard_price'] }};
                const vipPrice = {{ $movie['vip_price'] }};

                let standardSeats = parseInt(document.getElementById('standard_seats').value) || 0;
                if (standardSeats > {{ $movie['empty_standard'] }}) {
                    standardSeats = {{ $movie['empty_standard'] }};
                }

                if (standardSeats < 0) {
                    standardSeats = 0;
                }
                document.getElementById('standard_seats').value = standardSeats;

                let vipSeats = parseInt(document.getElementById('vip_seats').value) || 0;
                if (vipSeats > {{ $movie['empty_vip'] }}) {
                    vipSeats = {{ $movie['empty_vip'] }};
                }
                if (vipSeats < 0) {
                    vipSeats = 0;
                }
                document.getElementById('vip_seats').value = vipSeats;
                let totalPrice = (standardSeats * standardPrice) + (vipSeats * vipPrice);
                let qty
                @foreach($consumables as $consumable)
                    qty = parseInt(document.getElementById('consumable_{{ $consumable->id }}').value) || 0;
                    if (qty > 0) {
                        totalPrice += qty * {{ $consumable->price }};
                        document.getElementById('consumable_quantity_{{ $consumable->id }}').value = qty;
                    } else {
                        document.getElementById('consumable_quantity_{{ $consumable->id }}').value = 0;
                    }
                @endforeach

                document.getElementById('totalPrice').innerText = totalPrice;
                document.getElementById('standard_seats_input').value = standardSeats;
                document.getElementById('vip_seats_input').value = vipSeats;
            }
        </script>


    </x-slot>
    <div class="m-4 mb-0">
        <x-messages></x-messages>
    </div>
    <div class="flex">


        <div class="py-12 w-1/2">
            <div class="max-w-7xl mx-auto text-gray-200 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-7xl">
                        <!-- Thumbnail -->
                        <div class="">
                            <img src="{{ url('storage/'.$movie['thumbnail']) }}" alt="{{ $movie['name'] }} Thumbnail"
                                 class="w-full h-auto rounded-t">
                        </div>

                        <!-- Movie Information -->
                        <h3 class="m-4 text-4xl text-indigo-600 font-semibold">{{ $movie['name'] }}
                            <span
                                class="me-2 bg-indigo-100 text-indigo-800 text-lg font-medium px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">
                            {{ $movie['type']->value }}
                        </span>
                        </h3>
                        <p class="p-4 text-lg">{{ $movie['description'] }}</p>
                    </div>
                </div>
            </div>
            <div class="max-w-7xl mx-auto text-gray-200 sm:px-6 lg:px-8">
                <div class="mt-6 p-4 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <h4 class="text-2xl text-indigo-600 font-semibold mb-4">{{ __('General Information') }}</h4>
                    <!-- Standard Price & VIP Price -->
                    <div class="max-w-7xl flex space-x-4">
                        <div class="w-1/2">
                            <p><strong>{{ __('Standard Price:') }}</strong>
                                <span
                                    class="text-green-400 text-lg font-medium me-2">{{ $movie['standard_price'] }}$</span>
                            </p>
                        </div>
                        <div class="w-1/2">
                            <p><strong>{{ __('VIP Price:') }}</strong> <span
                                    class="text-green-400 text-lg font-medium me-2">{{ $movie['vip_price'] }}$</span>
                            </p>
                        </div>
                    </div>
                    <!-- Showing date & duration -->
                    <div class="max-w-7xl flex space-x-4 mt-4">
                        <div class="w-1/2">
                            <p><strong>{{ __('Showing Date:') }}</strong>
                                {{ $movie['showing_date'] }}
                            </p>
                        </div>
                        <div class="w-1/2">
                            <p>
                                <strong>{{ __('Duration:') }}</strong>
                                <span
                                    class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-green-400 border border-green-400">
                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                 fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm3.982 13.982a1 1 0 0 1-1.414 0l-3.274-3.274A1.012 1.012 0 0 1 9 10V6a1 1 0 0 1 2 0v3.586l2.982 2.982a1 1 0 0 1 0 1.414Z"/>
                            </svg>
                            {{ \Carbon\Carbon::parse($movie['start_time'])->diff(\Carbon\Carbon::parse($movie['end_time']))->format('%H:%I') }}
                            hours
                            </span>
                            </p>
                        </div>
                    </div>
                    <!-- Showing Date, Start Time, End Time & Time Period -->
                    <div class="max-w-7xl flex space-x-4 mt-4">
                        <div class="w-1/2">
                            <p>
                                <strong>{{ __('Start Time:') }}</strong> {{ Carbon::make($movie['start_time'])->format('h:i A') }}
                            </p>
                        </div>
                        <div class="w-1/2">
                            <p>
                                <strong>{{ __('End Time:') }}</strong> {{ Carbon::make($movie['end_time'])->format('h:i A') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="py-12 w-1/2">
            <div class="max-w-7xl mx-auto text-gray-200 sm:px-6 lg:px-8">
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    <div class="max-w-7xl">

                        <div class="flex">

                            <!-- Hall Information -->
                            <div class="w-1/2 mt-4">
                                <h4 class="text-2xl text-indigo-600 font-semibold mb-4">{{ __('Hall') }}</h4>
                                <p><strong>Name: </strong> {{ $movie['hall']['name'] }}</p>
                                @if(Auth::user()->role === Role::ADMIN)
                                    <p><strong>Standard Seats: </strong> {{ $movie['hall']['standard_seats'] }}</p>
                                    <p><strong>VIP Seats: </strong> {{ $movie['hall']['vip_seats'] }}</p>
                                @endif
                                <p><strong>Empty Standard Seats: </strong> {{ $movie['empty_standard'] }}</p>
                                <p><strong>Empty VIP Seats: </strong> {{ $movie['empty_vip'] }}</p>
                            </div>
                            <!-- Actors -->
                            @if (!empty($movie['actors']))
                                <div class="w-1/2 mt-4">
                                    <h4 class="text-2xl text-indigo-600 font-semibold mb-4">{{ __('Actors') }}</h4>
                                    <ul class="list-disc ml-5">
                                        @foreach ($movie['actors'] as $actor)
                                            <li>{{ $actor['name'] }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <!-- Trailers Slider -->
                        @if (!empty($movie['trailers']))
                            <div class="mt-4">
                                <h4 class="text-lg font-semibold">{{ __('Trailers') }}</h4>
                                <div class="relative w-full overflow-hidden" id="trailer-slider">
                                    <div class="flex" id="trailer-track">
                                        @foreach ($movie['trailers'] as $trailer)
                                            <div class="w-full flex-shrink-0 p-2">
                                                <video class="w-full" controls>
                                                    <source src="{{ url('storage/'.$trailer['video']) }}"
                                                            type="video/mp4">
                                                    {{ __('Your browser does not support the video tag.') }}
                                                </video>
                                            </div>
                                        @endforeach
                                    </div>
                                    <!-- Slider Controls -->
                                    <button
                                        class="absolute left-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white p-2"
                                        id="prev-btn">&lt;
                                    </button>
                                    <button
                                        class="absolute right-0 top-1/2 transform -translate-y-1/2 bg-gray-700 text-white p-2"
                                        id="next-btn">&gt;
                                    </button>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Slider JavaScript -->
    <script>
        const track = document.getElementById('trailer-track');
        const nextButton = document.getElementById('next-btn');
        const prevButton = document.getElementById('prev-btn');
        let currentIndex = 0;
        const slides = document.querySelectorAll('#trailer-track > div');
        const totalSlides = slides.length;

        function updateSliderPosition() {
            const width = slides[0].offsetWidth;
            track.style.transform = `translateX(-${currentIndex * width}px)`;
        }

        nextButton.addEventListener('click', function () {
            if (currentIndex < totalSlides - 1) {
                currentIndex++;
                updateSliderPosition();
            }
        });

        prevButton.addEventListener('click', function () {
            if (currentIndex > 0) {
                currentIndex--;
                updateSliderPosition();
            }
        });

        window.addEventListener('resize', updateSliderPosition);
    </script>

    <!-- Custom CSS for slider -->
    <style>
        #trailer-slider {
            position: relative;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }

        #trailer-track {
            display: flex;
            transition: transform 0.3s ease-in-out;
        }

        video {
            object-fit: cover;
            width: 100%;
            height: auto;
        }

        button {
            background-color: rgba(0, 0, 0, 0.5);
            border: none;
            cursor: pointer;
            padding: 0.5rem 1rem;
            font-size: 1.5rem;
        }
    </style>

</x-app-layout>
