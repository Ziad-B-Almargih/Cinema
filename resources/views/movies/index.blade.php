@php use App\Enums\Role;use Carbon\Carbon; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-300 dark:text-gray-200 leading-tight">
           Movies
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages/>

            <!-- Search Bar and Create Button Row -->
            <div class="mb-4 flex justify-between items-center">
                <!-- Search Form -->
                <form action="{{ route('movies.index') }}" method="GET" class="w-full max-w-sm flex">
                    <input type="text" name="search"
                           class="w-full dark:bg-gray-700 dark:text-gray-200 rounded-l px-4 py-2 focus:outline-none focus:ring-indigo-600 focus:border-indigo-600"
                           placeholder="Search movies..." value="{{ request('search') }}">
                    <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-r border-2 border-indigo-600 hover:border-indigo-500 hover:bg-indigo-500">
                        Search
                    </button>
                </form>

                @if(Auth::user()->role === Role::ADMIN)
                    <!-- Create Movie Button -->
                    <a href="{{ route('movies.create') }}"
                       class="ml-4 bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-500">
                        Create New Movie
                    </a>
                @endif
            </div>

            <!-- Movie Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($movies as $movie)
                    <a href="{{ route('movies.show', $movie) }}"
                       class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                        <img src="{{ url('storage/'.$movie->thumbnail) }}" alt="{{ $movie->name }}"
                             class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="text-lg my-2 font-bold dark:text-indigo-200"><strong>{{ $movie->name }}</strong>
                                <span
                                    class="me-2 bg-indigo-100 text-indigo-800 text-sm font-medium px-2.5 py-0.5 rounded-full dark:bg-indigo-900 dark:text-indigo-300">{{ $movie->type }}</span>
                            </h2>
                            <p class="my-2 text-sm font-bold dark:text-gray-200">{{ $movie->showing_date }}</p>
                            <p class="my-2 text-sm font-bold dark:text-gray-200">{{ Carbon::make($movie->start_time)->format('h:i A') }}</p>
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
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $movies->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
