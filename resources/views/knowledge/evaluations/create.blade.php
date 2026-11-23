<x-layouts.app title="Tulis Evaluasi Pertemuan">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('knowledge.index', ['tab' => 'evaluations']) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Knowledge Hub</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-gray-900">Catat Evaluasi Pertemuan</h1>
                <p class="text-sm text-gray-500 mt-1">Refleksi jalannya pembelajaran, performa relawan, atau kendala lapangan di Sekolah Rakyat Kejawan.</p>
            </div>

            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('knowledge.evaluations.store') }}" class="space-y-5">
                @csrf

                <!-- Pilih Pertemuan -->
                <div>
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Pertemuan yang Dievaluasi <span class="text-red-500">*</span>
                    </label>
                    <select id="event_id" name="event_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        <option value="">-- Pilih Pertemuan --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }} ({{ $event->batch?->name }}) - {{ $event->event_date?->format('d M Y') }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Evaluasi -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Tingkat Evaluasi <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                Evaluasi {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Isi Evaluasi -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Catatan Evaluasi / Refleksi Pengajaran <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="6" required
                              placeholder="Tuliskan evaluasi apa yang perlu ditingkatkan, kesiapan materi, keaktifan anak, atau koordinasi relawan saat mengajar..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-purple-500 focus:outline-none">{{ old('content') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('knowledge.index', ['tab' => 'evaluations']) }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Simpan Evaluasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
