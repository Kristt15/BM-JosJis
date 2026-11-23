<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - BM-JosJis</title>
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
                    <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-md text-sm font-medium bg-blue-700 transition">Dashboard</a>
                    
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
    <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
        <!-- Banner Selamat Datang -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
            <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}! 👋</h1>
            <p class="text-gray-600 mt-1">
                Anda masuk sebagai <strong class="text-blue-600">{{ auth()->user()->role->label() }}</strong> di Sistem Informasi Bidikmisi Mengajar.
            </p>
        </div>

        <!-- Kartu Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Relawan</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalVolunteers }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center text-blue-600 text-xl font-bold">
                        👥
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Mahasiswa aktif & alumni BM</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Anak Asuh SRK</p>
                        <p class="text-3xl font-bold text-emerald-600 mt-2">{{ $totalChildren }}</p>
                    </div>
                    <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center text-emerald-600 text-xl font-bold">
                        🎒
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Siswa Sekolah Rakyat Kejawan</p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Program Aktif</p>
                        <p class="text-xl font-bold text-indigo-600 mt-2 truncate">{{ $activeProgram?->name ?? 'Belum ada' }}</p>
                    </div>
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center text-indigo-600 text-xl font-bold">
                        📅
                    </div>
                </div>
                <p class="text-xs text-gray-400 mt-3">Periode tahun {{ $activeProgram?->year ?? date('Y') }}</p>
            </div>
        </div>

        <!-- Info Alur Operasional -->
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
            <h2 class="text-lg font-semibold text-blue-900 mb-2">📌 Status Sprint 1: Foundation Siap</h2>
            <p class="text-sm text-blue-700 leading-relaxed">
                Database, autentikasi role-based, dan data seeding telah berhasil diinisialisasi.
                Pada Sprint berikutnya, kita akan membuka modul <strong>People</strong> untuk mengelola data relawan dan anak asuh SRK secara lengkap.
            </p>
        </div>
    </main>
</body>
</html>
