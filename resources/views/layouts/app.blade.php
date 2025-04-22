<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'e-Filing KBSS') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts and Styles -->
    @livewireStyles
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-white shadow-md w-64 hidden md:block">
            <div class="p-6 bg-primary">
                <h1 class="text-white text-xl font-bold">e-Filing KBSS</h1>
            </div>
            <nav class="mt-6">
                <a href="{{ route('dashboard') }}" class="flex items-center px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                    <span class="mx-3">Dashboard</span>
                </a>
                <a href="{{ route('borrowings.create') }}" class="flex items-center px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('borrowings.create') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                    <span class="mx-3">Pinjaman File</span>
                </a>
                <a href="{{ route('history.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('history.index') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                    <span class="mx-3">Sejarah Peminjaman</span>
                </a>
                <a href="{{ route('files.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('files.index') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                    <span class="mx-3">File Management</span>
                </a>
                <a href="{{ route('departments.index') }}" class="flex items-center px-6 py-3 hover:bg-gray-100 {{ request()->routeIs('departments.*') ? 'bg-gray-100 border-l-4 border-primary' : '' }}">
                    <span class="mx-3">Department Management</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">@yield('header', 'Dashboard')</h2>
                    </div>
                    <div class="flex items-center">
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 focus:outline-none">
                                <span class="mr-2">{{ Auth::user()->name ?? 'User Name' }}</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                                <a href="{{ route('profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Content Area -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                @yield('content')
            </main>
        </div>
    </div>

    @livewireScripts
</body>

</html>