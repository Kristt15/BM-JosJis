<x-layouts.app title="Knowledge Hub">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Knowledge & Memori Organisasi</h1>
            <p class="text-sm text-gray-500 mt-1">Preservasi pengetahuan pengajaran, jurnal materi di SRK, dan arsip serah terima kepengurusan BM.</p>
        </div>

        <div class="flex items-center gap-2">
            @if($tab === 'notes')
                <a href="{{ route('knowledge.notes.create') }}"
                   class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow-sm transition">
                    <span>+</span>
                    <span>Tulis Catatan Mengajar</span>
                </a>
            @elseif($tab === 'handovers')
                @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
                    <a href="{{ route('knowledge.handovers.create') }}"
                       class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow-sm transition">
                        <span>+</span>
                        <span>Arsip Handover Baru</span>
                    </a>
                @endif
            @elseif($tab === 'evaluations')
                <a href="{{ route('knowledge.evaluations.create') }}"
                   class="inline-flex items-center gap-2 bg-purple-600 hover:bg-purple-700 text-white font-semibold px-4 py-2 rounded-lg text-sm shadow-sm transition">
                    <span>+</span>
                    <span>Tulis Evaluasi</span>
                </a>
            @endif
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl text-sm flex items-center gap-3">
            <span class="text-emerald-600 font-bold">✓</span>
            <div>{{ session('success') }}</div>
        </div>
    @endif

    <!-- Tabs Navigasi -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="flex space-x-6">
            <a href="{{ route('knowledge.index', ['tab' => 'notes']) }}"
               class="pb-3 text-sm font-semibold border-b-2 transition flex items-center gap-2 
               {{ $tab === 'notes' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>📝 Catatan Mengajar</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $tab === 'notes' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $teachingNotes->total() }}
                </span>
            </a>

            <a href="{{ route('knowledge.index', ['tab' => 'handovers']) }}"
               class="pb-3 text-sm font-semibold border-b-2 transition flex items-center gap-2 
               {{ $tab === 'handovers' ? 'border-indigo-600 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>📂 Dokumen Handover & LPJ</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $tab === 'handovers' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $handovers->total() }}
                </span>
            </a>

            <a href="{{ route('knowledge.index', ['tab' => 'evaluations']) }}"
               class="pb-3 text-sm font-semibold border-b-2 transition flex items-center gap-2 
               {{ $tab === 'evaluations' ? 'border-purple-600 text-purple-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                <span>🎯 Evaluasi Pertemuan</span>
                <span class="text-xs px-2 py-0.5 rounded-full {{ $tab === 'evaluations' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-600' }}">
                    {{ $evaluations->total() }}
                </span>
            </a>
        </nav>
    </div>

    <!-- ========================================== -->
    <!-- TAB 1: TEACHING NOTES (Catatan Mengajar) -->
    <!-- ========================================== -->
    @if($tab === 'notes')
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @forelse($teachingNotes as $note)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="flex items-center justify-between text-xs text-gray-400 mb-2">
                            <span>{{ $note->event?->name ?? 'Kegiatan BM' }} ({{ $note->event?->batch?->name ?? 'Batch' }})</span>
                            <span>{{ $note->created_at->format('d M Y') }}</span>
                        </div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $note->title }}</h2>
                        <p class="text-sm text-gray-600 line-clamp-3 mb-4 leading-relaxed">
                            {{ $note->content }}
                        </p>
                    </div>

                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold flex items-center justify-center">
                                {{ strtoupper(substr($note->volunteer?->user?->name ?? 'V', 0, 1)) }}
                            </div>
                            <span class="text-xs font-medium text-gray-700">{{ $note->volunteer?->user?->name ?? 'Relawan' }}</span>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('knowledge.notes.show', $note) }}"
                               class="text-xs text-blue-600 hover:text-blue-800 font-semibold transition">
                                Baca Lengkap →
                            </a>

                            @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']) || auth()->user()->volunteer?->id === $note->volunteer_id)
                                <form method="POST" action="{{ route('knowledge.notes.destroy', $note) }}"
                                      onsubmit="return confirm('Hapus catatan mengajar ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 bg-white rounded-xl p-12 text-center text-sm text-gray-400 border border-gray-200">
                    Belum ada catatan mengajar. Klik tombol "+ Tulis Catatan Mengajar" untuk mendokumentasikan materi pengajaran hari ini.
                </div>
            @endforelse
        </div>

        @if($teachingNotes->hasPages())
            <div class="mt-6">
                {{ $teachingNotes->appends(['tab' => 'notes'])->links() }}
            </div>
        @endif

    <!-- ========================================== -->
    <!-- TAB 2: HANDOVERS (Dokumen Serah Terima)   -->
    <!-- ========================================== -->
    @elseif($tab === 'handovers')
        <div class="space-y-4">
            @forelse($handovers as $handover)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:border-indigo-300 transition">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 uppercase tracking-wider">
                                {{ $handover->type->label() }}
                            </span>
                            <span class="text-xs text-gray-400">
                                {{ $handover->program?->name ?? 'Bidikmisi Mengajar' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">Diarsipkan: {{ $handover->created_at->format('d M Y') }}</span>
                    </div>

                    <h2 class="text-lg font-bold text-gray-900 mb-2">{{ $handover->title }}</h2>
                    <p class="text-sm text-gray-600 line-clamp-2 mb-4 leading-relaxed">
                        {{ $handover->content }}
                    </p>

                    <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                        <span class="text-xs text-gray-500 font-medium">
                            Disusun oleh: <strong>{{ $handover->creator?->name ?? 'Pengurus BM' }}</strong>
                        </span>

                        <div class="flex items-center gap-3">
                            <a href="{{ route('knowledge.handovers.show', $handover) }}"
                               class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold transition">
                                Pelajari Panduan →
                            </a>

                            @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']))
                                <form method="POST" action="{{ route('knowledge.handovers.destroy', $handover) }}"
                                      onsubmit="return confirm('Hapus dokumen handover ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 transition">
                                        Hapus
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 text-center text-sm text-gray-400 border border-gray-200">
                    Belum ada dokumen handover serah terima jabatan yang diarsipkan.
                </div>
            @endforelse
        </div>

        @if($handovers->hasPages())
            <div class="mt-6">
                {{ $handovers->appends(['tab' => 'handovers'])->links() }}
            </div>
        @endif

    <!-- ========================================== -->
    <!-- TAB 3: EVALUATIONS (Evaluasi Pertemuan)    -->
    <!-- ========================================== -->
    @elseif($tab === 'evaluations')
        <div class="space-y-4">
            @forelse($evaluations as $eval)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 mb-2">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $eval->type->value === 'individual' ? 'bg-amber-100 text-amber-800' : 'bg-purple-100 text-purple-800' }}">
                                Evaluasi {{ $eval->type->label() }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ $eval->event?->name ?? 'Kegiatan Pertemuan' }} ({{ $eval->event?->batch?->name ?? 'Batch' }})
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">{{ $eval->created_at->format('d M Y') }}</span>
                    </div>

                    <p class="text-sm text-gray-700 leading-relaxed my-3 bg-gray-50 p-4 rounded-lg border border-gray-100">
                        "{{ $eval->content }}"
                    </p>

                    <div class="flex items-center justify-between text-xs text-gray-500 pt-2">
                        <div>
                            Penilai: <strong>{{ $eval->evaluator?->user?->name ?? 'Relawan' }}</strong>
                            @if($eval->evaluated)
                                • Menilai: <strong>{{ $eval->evaluated->user?->name }}</strong>
                            @endif
                        </div>

                        @if(in_array(auth()->user()->role->value, ['admin', 'pengurus']) || auth()->user()->volunteer?->id === $eval->evaluator_volunteer_id)
                            <form method="POST" action="{{ route('knowledge.evaluations.destroy', $eval) }}"
                                  onsubmit="return confirm('Hapus evaluasi ini?')" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                    Hapus
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl p-12 text-center text-sm text-gray-400 border border-gray-200">
                    Belum ada catatan evaluasi pertemuan yang masuk.
                </div>
            @endforelse
        </div>

        @if($evaluations->hasPages())
            <div class="mt-6">
                {{ $evaluations->appends(['tab' => 'evaluations'])->links() }}
            </div>
        @endif
    @endif
</x-layouts.app>
