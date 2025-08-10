<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Konter +62 - Inventory Management System</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
    <div class="bg-gray-50 text-black/50">
        <div class="relative min-h-screen flex flex-col items-center justify-center">
            <div class="absolute top-0 right-0 p-6 text-right z-10">
                @auth
                    <a href="{{ url('/dashboard') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                        class="font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">
                        Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}"
                            class="ml-4 font-semibold text-gray-600 hover:text-gray-900 focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500 hidden">
                            Register
                        </a>
                    @endif
                @endauth
            </div>

            <div class="max-w-2xl mx-auto p-6 lg:p-8 text-center">
                <div class="flex flex-col items-center">
                    <img src="{{ asset('images/my-logo.png') }}" alt="Logo Konter +62" class="h-20 w-auto">
                    {{-- <p class="mt-4 text-lg font-semibold text-gray-700">Konter +62</p> --}}
                </div>

                <h1 class="mt-8 text-4xl font-bold tracking-tight text-gray-900 sm:text-6xl">
                    Inventory Management System
                </h1>

                <p class="mt-6 text-lg leading-8 text-gray-600">
                    Solusi modern untuk mengelola stok, melacak penjualan, dan menganalisis keuntungan bisnis konter
                    Anda secara efisien.
                </p>

                <div class="mt-10 flex items-center justify-center gap-x-6">
                    <a href="{{ route('login') }}"
                        class="rounded-md bg-[#00aeef] px-5 py-3 text-sm font-semibold text-white shadow-sm hover:bg-[#009cd7] focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-[#00aeef]">
                        Mulai Kelola Stok
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-semibold leading-6 text-gray-900 hidden">
                        Daftar Akun <span aria-hidden="true">→</span>
                    </a>
                </div>
            </div>

            <div class="absolute inset-x-0 top-[-10rem] -z-10 transform-gpu overflow-hidden blur-3xl sm:top-[-20rem]"
                aria-hidden="true">
                <div class="relative left-1/2 -z-10 aspect-[1155/678] w-[36.125rem] max-w-none -translate-x-1/2 rotate-[30deg] bg-gradient-to-tr from-[#ff80b5] to-[#9089fc] opacity-30 sm:left-[calc(50%-40rem)] sm:w-[72.1875rem]"
                    style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
