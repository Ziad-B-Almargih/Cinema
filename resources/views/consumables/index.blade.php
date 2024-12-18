<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Food & Drink
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-messages/>

            <!-- Button to Open Create Modal -->
            <div class="flex mb-4">
                <button onclick="openCreateModal()" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">
                    Create New Item
                </button>
            </div>

            <!-- Create Modal -->
            <div id="createModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
                <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
                    <h2 class="text-lg font-semibold text-gray-200">Create New Item</h2>
                    <form method="post" action="{{ route('consumables.store') }}" class="mt-6 space-y-6">
                        @csrf
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name" name="name" placeholder="Name" type="text" class="mt-1 block w-full" required autofocus autocomplete="name" />
                        </div>

                        <div>
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price" name="price" placeholder="Number" type="number" step="0.01" class="mt-1 block w-full" required autocomplete="price" />
                        </div>

                        <div>
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type" name="type" class="mt-1 block w-full dark:bg-gray-700 text-gray-300" required>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex justify-end mt-6">
                            <button type="button" class="mr-4 text-gray-400 hover:text-gray-300" onclick="closeCreateModal()">Cancel</button>
                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">Create Item</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Modal -->
            <div id="editModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-gray-900 bg-opacity-75">
                <div class="bg-gray-800 rounded-lg p-6 w-11/12 max-w-md">
                    <h2 class="text-lg font-semibold text-gray-200">Edit Consumable</h2>
                    <form action="" method="POST" id="editHallForm">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="hall_id" id="hall_id">

                        <div class="mt-4">
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input id="name_m" name="name" placeholder="Name" type="text"  class="mt-1 block w-full" required autofocus />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="price" :value="__('Price')" />
                            <x-text-input id="price_m"  name="price" placeholder="Number" type="number" step="0.01" class="mt-1 block w-full" required />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="type" :value="__('Type')" />
                            <select id="type_m" name="type" class="mt-1 block w-full dark:bg-gray-700 text-gray-300" required>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button type="button" class="mr-4 text-gray-400 hover:text-gray-300" onclick="closeModal()">Cancel</button>
                            <button type="submit" class="bg-indigo-600 text-white hover:bg-indigo-500 px-4 py-2 rounded">Save</button>
                        </div>
                    </form>
                </div>
            </div>



            <!-- Consumables List Table -->
            <div class="bg-gray-800 overflow-hidden shadow sm:rounded-lg mt-5">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-700">
                        <thead>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                        @foreach($consumables as $consumable)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $consumable->name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">{{ $consumable->price }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-300">
                                    {{ $consumable->type }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <a href="#" class="text-indigo-400 pr-6 hover:text-indigo-300" onclick="openModal('{{ $consumable->id }}', '{{ $consumable->name }}', '{{ $consumable->price }}', '{{ $consumable->type }}')">Edit</a>
                                    <form action="{{ route('consumables.destroy', $consumable) }}" method="POST" class="inline">
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

    <!-- Modal Scripts -->
    <script>
        function openCreateModal() {
            document.getElementById('createModal').classList.remove('hidden');
        }

        function closeCreateModal() {
            document.getElementById('createModal').classList.add('hidden');
        }

        function openModal(id, name, price, type) {
            document.getElementById('hall_id').value = id;
            document.getElementById('name_m').value = name;
            document.getElementById('price_m').value = price;
            document.getElementById('type_m').value = type;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editHallForm').action = "{{ url('consumables') }}/" + id;
        }

        function closeModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
