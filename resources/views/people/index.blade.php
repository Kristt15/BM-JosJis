<x-layouts.app title="People">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">People</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data relawan dan anak binaan SRK</p>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Card Volunteer -->
        <a href="{{ route('volunteers.index') }}"
           class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-blue-400 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center group-hover:bg-blue-200 transition">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-blue-600">{{ \App\Models\Volunteer::count() }}</span>
            </div>
            <div class="mt-4">
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-blue-700 transition">Kelola Relawan</h2>
                <p class="text-sm text-gray-500 mt-1">Data mahasiswa volunteer aktif dan alumni BM</p>
            </div>
            <div class="mt-4 text-sm text-blue-600 font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                Lihat Daftar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>

        <!-- Card Anak SRK -->
        <a href="{{ route('children.index') }}"
           class="group bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-purple-400 hover:shadow-md transition-all">
            <div class="flex items-start justify-between">
                <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center group-hover:bg-purple-200 transition">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold text-purple-600">{{ \App\Models\Child::count() }}</span>
            </div>
            <div class="mt-4">
                <h2 class="text-lg font-semibold text-gray-900 group-hover:text-purple-700 transition">Kelola Anak SRK</h2>
                <p class="text-sm text-gray-500 mt-1">Data anak binaan Sekolah Rakyat Kejawan</p>
            </div>
            <div class="mt-4 text-sm text-purple-600 font-medium flex items-center gap-1 group-hover:gap-2 transition-all">
                Lihat Daftar
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </div>
        </a>
    </div>
</x-layouts.app>
