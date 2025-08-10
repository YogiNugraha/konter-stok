<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Akun Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- (Gunakan struktur <x-app-layout> yang sama seperti halaman lain) --}}
                    <form method="POST" action="{{ route('users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                            <input id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                type="text" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="email">Email</label>
                            <input id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <select name="role" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                id="role">
                                <option value="kasir" {{ old('role', $user->role) == 'kasir' ? 'selected' : '' }}>Kasir
                                </option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                                </option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700" for="password">Password</label>
                            <input id="password" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                type="password" name="password">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700"
                                for="password_confirmation">Konfirmasi Password</label>
                            <input id="password_confirmation"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" type="password"
                                name="password_confirmation">
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                                type="submit">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
