@props(['title' => 'Sultaf Owner', 'pageTitle' => 'Dashboard'])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-red-900 text-white shadow-md">
            <div class="p-6 text-2xl font-bold tracking-wider">SULTAF OWNER</div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="{{ route('owner.dashboard') }}" class="block py-2.5 px-4 rounded transition hover:bg-red-800">Dashboard</a>
                <a href="{{ route('owner.laporan') }}" class="block py-2.5 px-4 rounded transition hover:bg-red-800">Laporan</a>
                <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded transition hover:bg-red-800">View Website</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full text-left py-2.5 px-4 rounded transition hover:bg-red-800 text-red-200">Logout</button>
                </form>
            </nav>
        </aside>

        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-gray-800">{{ $pageTitle }}</h1>
                <span class="text-sm font-medium text-gray-600">{{ auth()->user()->name ?? auth()->user()->username }}</span>
            </header>
            <main class="p-8">
                @if (session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-800 px-4 py-3 text-sm">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="mb-4 rounded-lg bg-red-100 text-red-800 px-4 py-3 text-sm">{{ session('error') }}</div>
                @endif
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>
