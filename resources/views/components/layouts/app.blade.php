<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Bidikmisi Mengajar' }} - BM-JosJis</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen text-gray-800">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center space-x-3">
                    <span class="text-xl font-bold tracking-tight">BM-JosJis</span>
                    <span class="text-xs bg-blue-700 px-2.5 py-1 rounded-full uppercase tracking-wider font-semibold">
                        {{ auth()->user()->role->label() }}
                    </span>
                </div>

                <div class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">Dashboard</a>
                    
                    @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
                        <a href="{{ route('people.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">People</a>
                        <a href="{{ route('programs.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">Program</a>
                    @endif

                    <a href="{{ route('activities.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">Aktivitas</a>
                    <a href="{{ route('knowledge.index') }}" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition">Knowledge</a>
                </div>

                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-blue-100 hidden sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition shadow-sm">
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>
</body>
</html>
