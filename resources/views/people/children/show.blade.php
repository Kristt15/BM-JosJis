<x-layouts.app title="Profil Anak SRK">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('children.index') }}" class="hover:text-blue-600 transition">Anak SRK</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $child->name }}</span>
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
                <div class="w-20 h-20 rounded-full {{ $child->gender === 'female' ? 'bg-pink-100' : 'bg-indigo-100' }} flex items-center justify-center mx-auto mb-4">
                    <span class="{{ $child->gender === 'female' ? 'text-pink-700' : 'text-indigo-700' }} font-bold text-3xl">
                        {{ strtoupper(substr($child->name, 0, 1)) }}
                    </span>
                </div>

                <h2 class="text-lg font-bold text-gray-900">{{ $child->name }}</h2>
                @if($child->nickname)
                    <p class="text-sm text-gray-500 mt-0.5">"{{ $child->nickname }}"</p>
                @endif

                <!-- Gender Badge -->
                <div class="mt-3">
                    @if($child->gender === 'male')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            ♂ Laki-laki
                        </span>
                    @elseif($child->gender === 'female')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-pink-100 text-pink-800">
                            ♀ Perempuan
                        </span>
                    @endif
                </div>

                <!-- Info -->
                <div class="mt-5 pt-5 border-t text-left space-y-3">
                    <div class="flex items-start gap-3">
                        <span class="text-gray-400 w-5 mt-0.5">🏫</span>
                        <div>
                            <div class="text-xs text-gray-500">Kelas</div>
                            <div class="text-sm font-medium text-gray-900">{{ $child->grade ?? '-' }}</div>
                        </div>
                    </div>
                    @if($child->school)
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 w-5 mt-0.5">📚</span>
                            <div>
                                <div class="text-xs text-gray-500">Sekolah</div>
                                <div class="text-sm font-medium text-gray-900">{{ $child->school }}</div>
                            </div>
                        </div>
                    @endif
                    @if($child->birth_date)
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 w-5 mt-0.5">🎂</span>
                            <div>
                                <div class="text-xs text-gray-500">Tanggal Lahir</div>
                                <div class="text-sm font-medium text-gray-900">{{ $child->birth_date->format('d M Y') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($child->address)
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 w-5 mt-0.5">📍</span>
                            <div>
                                <div class="text-xs text-gray-500">Alamat</div>
                                <div class="text-sm font-medium text-gray-900">{{ $child->address }}</div>
                            </div>
                        </div>
                    @endif
                    @if($child->notes)
                        <div class="flex items-start gap-3">
                            <span class="text-gray-400 w-5 mt-0.5">📝</span>
                            <div>
                                <div class="text-xs text-gray-500">Catatan</div>
                                <div class="text-sm text-gray-700">{{ $child->notes }}</div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="mt-5 pt-5 border-t flex flex-col gap-2">
                    <a href="{{ route('children.edit', $child) }}"
                       class="w-full bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 rounded-lg transition text-center">
                        Edit Data
                    </a>
                    <form method="POST" action="{{ route('children.destroy', $child) }}"
                          onsubmit="return confirm('Yakin ingin menghapus data anak ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="w-full bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium py-2 rounded-lg transition">
                            Hapus Data
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Riwayat & Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Riwayat Batch -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-base font-semibold text-gray-900 mb-4">Riwayat Batch</h3>

                @if($child->batches->isEmpty())
                    <div class="text-center py-8 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        <p class="text-sm">Belum terdaftar dalam batch manapun</p>
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($child->batches as $batch)
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <div class="text-sm font-semibold text-gray-900">{{ $batch->name }}</div>
                                <div class="text-xs text-gray-500 mt-0.5">
                                    {{ $batch->phase?->program?->name ?? '-' }} &bull; {{ $batch->phase?->name ?? '-' }}
                                </div>
                                @if($batch->start_date)
                                    <div class="text-xs text-gray-400 mt-1">
                                        {{ $batch->start_date->format('d M Y') }}
                                        @if($batch->end_date) — {{ $batch->end_date->format('d M Y') }} @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Mentor Assignments -->
            @if($child->mentorAssignments->isNotEmpty())
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h3 class="text-base font-semibold text-gray-900 mb-4">Mentor</h3>
                    <div class="space-y-2">
                        @foreach($child->mentorAssignments as $assignment)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                                <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center">
                                    <span class="text-blue-700 font-semibold text-xs">
                                        {{ strtoupper(substr($assignment->volunteer?->name ?? '?', 0, 1)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $assignment->volunteer?->name ?? '-' }}</div>
                                    <div class="text-xs text-gray-500">{{ $assignment->volunteer?->nim ?? '' }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Stats -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <div class="text-3xl font-bold text-blue-600">{{ $child->batches->count() }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Batch</div>
                </div>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 text-center">
                    <div class="text-3xl font-bold text-purple-600">{{ $child->mentorAssignments->count() }}</div>
                    <div class="text-sm text-gray-500 mt-1">Total Mentor</div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
