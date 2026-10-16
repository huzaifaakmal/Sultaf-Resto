<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sultaf Admin Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-red-900 text-white shadow-md">
            <div class="p-6 text-2xl font-bold tracking-wider">SULTAF ADMIN</div>
            <nav class="mt-6 px-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition hover:bg-red-800">Dashboard</a>
                <a href="{{ route('menu.index') }}" class="block py-2.5 px-4 rounded transition hover:bg-red-800">View Website</a>
                <form method="POST" action="{{ route('logout') }}" class="mt-8">
                    @csrf
                    <button type="submit" class="w-full text-left py-2.5 px-4 rounded transition hover:bg-red-800 text-red-200">Logout</button>
                </form>
            </nav>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                <h1 class="text-xl font-semibold text-gray-800">Dashboard Overview</h1>
                <span class="text-sm font-medium text-gray-600">{{ auth()->user()->name ?? 'Administrator' }}</span>
            </header>
            <main class="p-8">
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>