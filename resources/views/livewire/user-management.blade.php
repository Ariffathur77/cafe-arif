<div>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-xl sm:rounded-lg">
                <div class="p-6">
                    @if (session()->has('message'))
                        <div class="px-4 py-3 mb-4 text-teal-900 bg-teal-100 border-t-4 border-teal-500 rounded-b shadow-md"
                            role="alert">
                            <p class="font-bold">{{ session('message') }}</p>
                        </div>
                    @endif
                    <button wire:click="create()"
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">+ Tambah User
                        Baru</button>
                </div>

                @if ($isModalOpen)
                    <div class="fixed inset-0 z-10 overflow-y-auto">
                        <div
                            class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true">
                                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                            </div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen"
                                aria-hidden="true">&#8203;</span>
                            <div
                                class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                                <h3 class="text-lg font-medium leading-6 text-gray-900">
                                    {{ $user_id ? 'Edit User' : 'Tambah User Baru' }}</h3>
                                <form>
                                    <div class="mt-4">
                                        <label for="name" class="block text-sm font-bold text-gray-700">Nama</label>
                                        <input type="text" wire:model.defer="name" id="name"
                                            class="w-full px-3 py-2 border rounded">
                                        @error('name')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="email"
                                            class="block text-sm font-bold text-gray-700">Email</label>
                                        <input type="email" wire:model.defer="email" id="email"
                                            class="w-full px-3 py-2 border rounded">
                                        @error('email')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="role_id" class="block text-sm font-bold text-gray-700">Role</label>
                                        <select wire:model.defer="role_id" id="role_id"
                                            class="w-full px-3 py-2 border rounded">
                                            <option value="">Pilih Role</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('role_id')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="mt-4">
                                        <label for="password"
                                            class="block text-sm font-bold text-gray-700">Password</label>
                                        <input type="password" wire:model.defer="password" id="password"
                                            class="w-full px-3 py-2 border rounded"
                                            placeholder="{{ $user_id ? 'Kosongkan jika tidak diubah' : '' }}">
                                        @error('password')
                                            <span class="text-red-500">{{ $message }}</span>
                                        @enderror
                                    </div>
                                    <div class="px-4 py-3 mt-4 sm:px-6 sm:flex sm:flex-row-reverse">
                                        <button type="button" wire:click.prevent="store()"
                                            class="inline-flex justify-center w-full px-4 py-2 text-white bg-blue-600 border rounded-md sm:ml-3 sm:w-auto sm:text-sm">Simpan</button>
                                        <button type="button" wire:click="closeModal()"
                                            class="inline-flex justify-center w-full px-4 py-2 mt-3 text-gray-700 bg-white border rounded-md sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="p-6 border-t border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Nama</th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Role</th>
                                <th class="relative px-6 py-3"><span class="sr-only">Aksi</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($users as $user)
                                <tr>
                                    <td class="px-6 py-4">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->role->name }}</td>
                                    <td class="px-6 py-4 text-sm font-medium text-right">
                                        <button wire:click="edit({{ $user->id }})"
                                            class="text-indigo-600 hover:text-indigo-900">Edit</button>
                                        <button wire:click="delete({{ $user->id }})" @confirm('Yakin ingin hapus user ini?')
                                            class="ml-4 text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">Belum ada data user.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
