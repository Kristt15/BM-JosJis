<x-layouts.app title="Tulis Catatan Mengajar">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('knowledge.index', ['tab' => 'notes']) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Knowledge Hub</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-gray-900">Tulis Catatan Mengajar (Teaching Note)</h1>
                <p class="text-sm text-gray-500 mt-1">Dokumentasikan materi pembelajaran, respon anak, dan catatan penting dari pertemuan hari ini.</p>
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

            <form method="POST" action="{{ route('knowledge.notes.store') }}" class="space-y-5">
                @csrf

                <!-- Pilih Pertemuan / Event -->
                <div>
                    <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih Pertemuan Mengajar <span class="text-red-500">*</span>
                    </label>
                    <select id="event_id" name="event_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Pertemuan --</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->name }} - {{ $event->batch?->name }} ({{ $event->event_date?->format('d M Y') }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Judul Materi -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Judul Materi Pengajaran <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Belajar Pecahan Sederhana dan Nilai Tempat Angka"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Media / Alat Pembelajaran -->
                <div>
                    <label for="materials_used" class="block text-sm font-medium text-gray-700 mb-1">
                        Media & Alat Ajar yang Digunakan
                    </label>
                    <input type="text" id="materials_used" name="materials_used" value="{{ old('materials_used') }}"
                           placeholder="Contoh: Flashcard angka bergambar, kertas lipat origami, papan tulis kecil"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Isi Catatan / Jurnal -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Isi Jurnal Catatan Mengajar <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="6" required
                              placeholder="Ceritakan metode apa yang berhasil, kesulitan yang dihadapi anak-anak SRK, dan tips untuk relawan yang akan mengajar di pertemuan selanjutnya..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('content') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('knowledge.index', ['tab' => 'notes']) }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Publikasikan Catatan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
