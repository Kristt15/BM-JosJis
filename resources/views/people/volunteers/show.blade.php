<x-layouts.app title="Profil Volunteer">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('volunteers.index') }}" class="hover:text-blue-600 transition">Volunteer</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $volunteer->name }}</span>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Card -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                <!-- Avatar -->
                <div class="w-20 h-20 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                    <span class="text-blue-700 font-bold text-3xl">{{ strtoupper(substr($volunteer->name, 0, 1)) }}</span>
                </div>

                <h2 class="text-lg font-bold text-gray-900">{{ $volunteer->name }}</h2>
                <p class="text-sm text-gray-500 mt-1">{{ $volunteer->user?->email }}</p>

                <!-- Status Badge -->
                <div class="mt-3">
                    @if($volunteer->status->value === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            ● Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-gray-100 text-gray-700">
                            ● Alumni
                        </span>
                    @endif
                </div>

                <!-- Info Akademik -->
                <div class="mt-5 pt-5 border-t text-left space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-gray-400 text-sm w-5 mt-0.5">🎓</span>
                        <div>
                            <div class="text-xs text-gray-500">Jurusan</div>
                            <div class="text-sm font-medium text-gray-900">{{ $volunteer->major }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-gray-400 text-sm w-5 mt-0.5">📋</span>
                        <div>
                            <div class="text-xs text-gray-500">NIM</div>
                            <div class="text-sm font-mono font-medium text-gray-900">{{ $volunteer->nim }}</div>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <span class="text-gray-400 text-sm w-5 mt-0.5">📅</span>
                        <div>
                            <div class="text-xs text-gray-500">Angkatan</div>
                            <div class="text-sm font-medium text-gray-900">{{ $volunteer->generation }}</div>
                        </div>
                    </div>
                    @if($volunteer->phone)
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 text-sm w-5 mt-0.5">📱</span>
                            <div>
                                <div class="text-xs text-gray-500">Telepon</div>
                                <div class="text-sm font-medium text-gray-900">{{ $volunteer->phone }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-5 pt-5 border-t flex flex-col gap-2">
                    <a href="{{ route('volunteers.edit', $volunteer) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition text-center">
                        Edit Profil
                    </a>
                    <form method="POST" action="{{ route('volunteers.destroy', $volunteer) }}"
                          onsubmit="return confirm('Yakin ingin menghapus volunteer ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium py-2 rounded-lg transition">
                            Hapus Volunteer
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat Batch -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Batch</h3>

                @if($volunteer->batches->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">Belum tergabung dalam batch manapun</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($volunteer->batches as $batch)
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $batch->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ $batch->phase?->program?->name ?? '-' }} &bull;
                                        {{ $batch->phase?->name ?? '-' }}
                                    </div>
                                    @if($batch->start_date)
                                        <div class="text-xs text-gray-400 mt-1">
                                            {{ $batch->start_date->format('d M Y') }}
                                            @if($batch->end_date) — {{ $batch->end_date->format('d M Y') }} @endif
                                        </div>
                                    @endif
                                </div>
                                @if($batch->pivot->role)
                                    <span class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full font-medium">
                                        {{ $batch->pivot->role }}
                                    </span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Stats Card -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $volunteer->batches->count() }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Batch</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <div class="text-3xl font-bold text-green-600">
                        {{ $volunteer->batches->pluck('phase.program_id')->unique()->count() }}
                    </div>
                    <div class="text-sm text-gray-500 mt-1">Program Diikuti</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
