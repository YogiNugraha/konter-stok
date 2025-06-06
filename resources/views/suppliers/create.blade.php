<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Supplier Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <form method="POST" action="{{ route('suppliers.store') }}">
                        @csrf {{-- Wajib untuk keamanan Laravel --}}

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
                            <input type="text" name="name" id="name"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('name') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="contact_person" class="block text-sm font-medium text-gray-700">Kontak
                                Person</label>
                            <input type="text" name="contact_person" id="contact_person"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('contact_person') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="block text-sm font-medium text-gray-700">Telepon</label>
                            <input type="number" name="phone" id="phone"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                value="{{ old('phone') }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
                            <textarea name="address" id="address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('address') }}</textarea>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('products.index') }}"
                                class="text-sm text-gray-600 hover:text-gray-900 mr-4">
                                Batal
                            </a>
                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
