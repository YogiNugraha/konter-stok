<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengelolaan Akun Kasir') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Tampilkan pesan sukses --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                            role="alert">
                            <strong class="font-bold">Sukses!</strong>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                    <a href="{{ route('users.create') }}"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">
                        + Tambah Kasir
                    </a>
                    <table class="min-w-full w-full leading-normal">
                        <thead>
                            <tr>
                                <th class="px-5 py-3 border-b-2 ...">Nama</th>
                                <th class="px-5 py-3 border-b-2 ...">Email</th>
                                <th class="px-5 py-3 border-b-2 ...">Role</th>
                                <th class="px-5 py-3 border-b-2 ...">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($users as $user)
                                <tr>
                                    <td class="px-5 py-5 border-b ...">{{ $user->name }}</td>
                                    <td class="px-5 py-5 border-b ...">{{ $user->email }}</td>
                                    <td class="px-5 py-5 border-b ...">
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->role === 'admin' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                            {{ $user->role }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-5 border-b ...">
                                        <div class="mb-4 flex items-center space-x-4">
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                            {{-- Tombol Hapus hanya muncul jika user yang akan dihapus BUKAN user yang sedang login --}}
                                            @if (auth()->user()->id !== $user->id)
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Anda yakin akan menghapus {{ $user->name }}?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900">Hapus</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-10">Tidak ada data pengguna.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
