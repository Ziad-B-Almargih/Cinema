<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Halls') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages/>

            <!-- Button to Open Create Modal -->
            <div class="mb-4">
                <button
                    class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded"
                    onclick="openCreateModal()">
                    {{ __('Create New Hall') }}
                </button>
            </div>

            <!-- Hall List Table -->
            <div class="bg-gray-800 overflow-hidden shadow sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Standard Seats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">VIP Seats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($halls as $hall)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $hall->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $hall->standard_seats }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $hall->vip_seats }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-indigo-400 pr-6 hover:text-indigo-300" onclick="openEditModal('{{ $hall->id }}', '{{ $hall->name }}', '{{ $hall->standard_seats }}', '{{ $hall->vip_seats }}')">Edit</a>
                                    <form action="{{ route('halls.destroy', $hall) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-400 hover:text-red-300">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Hall Modal -->
    <div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
        <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
            <h2 class="text-lg font-semibold text-gray-200">Create New Hall</h2>
            <form method="post" action="{{ route('halls.store') }}" class="mt-6 space-y-6">
                @csrf

                <div class="mt-4">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" placeholder="Name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                </div>

                <div class="mt-4">
                    <x-input-label for="standard_seats" :value="__('Standard Seats')" />
                    <x-text-input id="standard_seats" name="standard_seats" placeholder="Number" type="number" class="mt-1 block w-full" required autocomplete="standard_seats" />
                </div>

                <div class="mt-4">
                    <x-input-label for="vip_seats" :value="__('VIP Seats')" />
                    <x-text-input id="vip_seats" name="vip_seats" placeholder="Number" type="number" class="mt-1 block w-full" required autocomplete="vip_seats" />
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" class="mr-4 text-gray-400 hover:text-gray-300" onclick="closeCreateModal()">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">Create Hall</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Hall Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
        <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
            <h2 class="text-lg font-semibold text-gray-200">Edit Hall</h2>
            <form action="" method="POST" id="editHallForm">
                @csrf
                @method('PUT')
                <input type="hidden" name="hall_id" id="hall_id">

                <div class="mt-4">
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name_m" name="name" placeholder="Name" type="text" class="mt-1 block w-full" required autofocus />
                </div>

                <div class="mt-4">
                    <x-input-label for="standard_seats" :value="__('Standard Seats')" />
                    <x-text-input id="standard_seats_m" name="standard_seats" placeholder="Number" type="number" class="mt-1 block w-full" required />
                </div>

                <div class="mt-4">
                    <x-input-label for="vip_seats" :value="__('VIP Seats')" />
                    <x-text-input id="vip_seats_m" name="vip_seats" placeholder="Number" type="number" class="mt-1 block w-full" required />
                </div>

                <div class="mt-6 flex justify-end">
                    <button type="button" class="mr-4 text-gray-400 hover:text-gray-300" onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Open and Close Create Modal
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        // Open and Close Edit Modal
        function openEditModal(id, name, standardSeats, vipSeats) {
            document.getElementById('hall_id').value = id;
            document.getElementById('name_m').value = name;
            document.getElementById('standard_seats_m').value = standardSeats;
            document.getElementById('vip_seats_m').value = vipSeats;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editHallForm').action = "{{ url('halls') }}/" + id;
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
