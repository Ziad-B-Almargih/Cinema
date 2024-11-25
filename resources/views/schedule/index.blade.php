<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Schedule') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages/>
            <!-- Create New Schedule Form -->
                <div class="max-w-xl">
                    <div class="mb-4">
                        <button onclick="openCreateModal()"
                                class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">
                            {{ __('Create New Schedule') }}
                        </button>
                    </div>
                </div>

            <div id="createModal"
                 class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
                <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
                    <h2 class="text-lg font-semibold text-gray-200">{{ __('Create New Schedule') }}</h2>
                    <form method="post" action="{{ route('schedules.store') }}" class="mt-6 space-y-6">
                        @csrf

                        <div class="mt-4">
                            <x-input-label for="start_time" :value="__('Start Time')"/>
                            <input id="start_time" name="start_time" value="{{ old('start_time') }}" type="time"
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="end_time" :value="__('End Time')"/>
                            <x-text-input id="end_time" name="end_time" value="{{ old('end_time') }}" type="time"
                                          class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required/>
                        </div>

                        <div class="mt-4">
                            <x-input-label for="date" :value="__('Showing Date')"/>
                            <x-text-input id="date" name="date" value="{{ old('date') }}" type="date"
                                          class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm" required/>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" class="mr-4 text-gray-400 hover:text-gray-300" onclick="closeCreateModal()">Cancel</button>
                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">Create Schedule</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Schedule List Table -->
            <div class="bg-gray-800 overflow-hidden shadow sm:rounded-lg mt-5">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Date
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Start Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                End Time
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($schedules as $schedule)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $schedule->date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $schedule->start_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $schedule->end_time }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-indigo-400 pr-6 hover:text-indigo-300"
                                       onclick="openModal('{{ $schedule->id }}', '{{ $schedule->date }}', '{{ $schedule->start_time }}', '{{ $schedule->end_time }}')">Edit</a>
                                    <form action="{{ route('schedules.destroy', $schedule) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach

                        <!-- Modal -->
                        <div id="editModal"
                             class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
                            <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
                                <h2 class="text-lg font-semibold text-gray-200">Edit Schedule</h2>
                                <form action="" method="POST" id="editScheduleForm">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="schedule_id" id="schedule_id">

                                    <div class="mt-4">
                                        <x-input-label for="date_m" :value="__('Date')"/>
                                        <x-text-input id="date_m" name="date" type="date"
                                                      class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                      required/>
                                        <x-input-error class="mt-2" :messages="$errors->get('showing_date')"/>
                                    </div>

                                    <div class="mt-4">
                                        <x-input-label for="start_time_m" :value="__('Start Time')"/>
                                        <x-text-input id="start_time_m" name="start_time" type="time"
                                                      class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                      required/>
                                        <x-input-error class="mt-2" :messages="$errors->get('start_time')"/>
                                    </div>

                                    <div class="mt-4">
                                        <x-input-label for="end_time_m" :value="__('End Time')"/>
                                        <x-text-input id="end_time_m" name="end_time" type="time"
                                                      class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm"
                                                      required/>
                                        <x-input-error class="mt-2" :messages="$errors->get('end_time')"/>
                                    </div>

                                    <div class="mt-6 flex justify-end">
                                        <button type="button" class="mr-4 text-gray-400 hover:text-gray-300"
                                                onclick="closeModal()">Cancel
                                        </button>
                                        <button type="submit"
                                                class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <style>
        input[type="datetime-local"] {
            background-color: #1a202c;
            color: #fff;
        }
    </style>
    <script>
        function openModal(id, date, startTime, endTime) {

            document.getElementById('schedule_id').value = id;
            document.getElementById('date_m').value = date;
            document.getElementById('start_time_m').value = startTime;
            document.getElementById('end_time_m').value = endTime;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editScheduleForm').action = "{{ url('schedules') }}/" + id;
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

    </script>
</x-app-layout>
