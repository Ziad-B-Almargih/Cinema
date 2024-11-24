<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <x-back-button></x-back-button>
            {{ __('Create New Movie') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages />
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-7xl">
                    <form method="post" action="{{ route('movies.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Movie Name & Description -->
                        <div class="flex space-x-4">
                            <div class="w-1/2">
                                <x-input-label for="name" :value="__('Movie Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus value="{{  old('name') }}"/>
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>
                            <div class="w-1/2">
                                <x-input-label for="description" :value="__('Description')" />
                                <textarea id="description" name="description"   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required>{{old('description')}}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('description')" />
                            </div>
                        </div>

                        <!-- Thumbnail & Showing Date -->
                        <div class="flex space-x-4 mt-4">
                            <div class="w-1/2">
                                <x-input-label for="thumbnail" :value="__('Thumbnail')" />
                                <input id="thumbnail" name="thumbnail"  value="{{  old('thumbnail') }}" type="file" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required />
                                <x-input-error class="mt-2" :messages="$errors->get('thumbnail')" />
                            </div>
                            <div class="w-1/2">
                                <x-input-label for="schedule_id" :value="__('Schedule')" />
                                <select id="schedule_id" name="schedule_id" class="mt-1 inline w-full dark:bg-gray-700 text-gray-300" required>
                                    @foreach($schedules as $schedule)
                                        <option value="{{ $schedule->id }}" {{ old('schedule_id') == $schedule->id ? 'selected' : '' }}>
                                            {{ $schedule->date }} from {{ $schedule->start_time }} to {{ $schedule->end_time }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('schedule_id')" />
                            </div>
                        </div>

                        <!-- Standard Price & VIP Price -->
                        <div class="flex space-x-4 mt-4">
                            <div class="w-1/2">
                                <x-input-label for="standard_price" :value="__('Standard Price')" />
                                <x-text-input id="standard_price" name="standard_price"  value="{{  old('standard_price') }}" type="number" min="1" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('standard_price')" />
                            </div>
                            <div class="w-1/2">
                                <x-input-label for="vip_price" :value="__('VIP Price')" />
                                <x-text-input id="vip_price" name="vip_price"  value="{{  old('vip_price') }}" type="number" min="1" class="mt-1 block w-full" required />
                                <x-input-error class="mt-2" :messages="$errors->get('vip_price')" />
                            </div>
                        </div>

                        <!-- Hall & Movie Type -->
                        <div class="flex space-x-4 mt-4">
                            <div class="w-1/2">
                                <x-input-label for="hall_id" :value="__('Hall')" />
                                <select id="hall_id" name="hall_id" class="mt-1 inline w-full dark:bg-gray-700 text-gray-300" required>
                                    @foreach($halls as $hall)
                                        <option value="{{ $hall->id }}" {{ old('hall_id') == $hall->id ? 'selected' : '' }}>
                                            {{ $hall->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('hall_id')" />
                            </div>

                            <div class="w-1/2">
                                <x-input-label for="type" :value="__('Movie Type')" />
                                <select id="type" name="type" class="mt-1 inline w-full dark:bg-gray-700 text-gray-300" required>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" {{ old('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('type')" />
                            </div>

                        </div>

                        <!-- Trailers -->
                        <div class="mt-4">
                            <x-input-label for="trailers" :value="__('Trailers (Optional)')" />
                            <div id="trailers-wrapper">

                            </div>
                            <button type="button" id="add-trailer" class="mt-2 text-blue-500">+ Add Trailer</button>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('Create Movie') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts for Adding/Removing Trailers -->
    <script>
        document.getElementById('add-trailer').addEventListener('click', function () {
            let newTrailer = document.createElement('div');
            newTrailer.innerHTML = `
                 <div class="trailer-group mb-2 flex space-x-4">
                     <input type="file" name="trailers[]" accept="video/*" class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" />
                     <button type="button" class="remove-trailer text-red-500">Remove</button>
                 </div>
            `
            document.getElementById('trailers-wrapper').appendChild(newTrailer)
        });

        document.getElementById('trailers-wrapper').addEventListener('click', function (e) {
            if (e.target.classList.contains('remove-trailer')) {
                e.target.closest('.trailer-group').remove();
            }
        });
    </script>


    <!-- Custom CSS for dark file input and datetime picker -->
    <style>
        input[type="file"]::file-selector-button {
            background-color: #1a202c; /* Dark background */
            color: #fff; /* White text */
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="datetime-local"] {
            background-color: #1a202c;
            color: #fff;
        }
    </style>
</x-app-layout>
