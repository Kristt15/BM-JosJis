<x-layouts.app title="Arsip Dokumen Handover">
    <div class="max-w-2xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('knowledge.index', ['tab' => 'handovers']) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Knowledge Hub</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-gray-900">Arsipkan Dokumen Serah Terima (Handover / LPJ)</h1>
                <p class="text-sm text-gray-500 mt-1">Simpan catatan pedoman operasional dan pelajaran penting untuk pengurus BM generasi selanjutnya.</p>
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

            <form method="POST" action="{{ route('knowledge.handovers.store') }}" class="space-y-5">
                @csrf

                <!-- Pilih Program BM -->
                <div>
                    <label for="program_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Program Periode BM <span class="text-red-500">*</span>
                    </label>
                    <select id="program_id" name="program_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach($programs as $program)
                            <option value="{{ $program->id }}" {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                {{ $program->name }} (Tahun {{ $program->year }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tipe Jabatan -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                        Posisi / Jabatan Pengurus <span class="text-red-500">*</span>
                    </label>
                    <select id="type" name="type" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        @foreach($types as $type)
                            <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                Handover {{ $type->label() }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Judul Dokumen -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
                        Judul Dokumen Handover <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           placeholder="Contoh: Panduan Operasional Pengelolaan Relawan & Hubungan Sekolah SRK"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Isi Dokumen -->
                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
                        Isi Panduan & Catatan Serah Terima Jabatan <span class="text-red-500">*</span>
                    </label>
                    <textarea id="content" name="content" rows="8" required
                              placeholder="Tuliskan evaluasi kepengurusan, alur kerja rutin, kontak penting pihak mitra SRK, masalah umum yang sering timbul, dan rekomendasi untuk pengurus generasi berikutnya..."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('content') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('knowledge.index', ['tab' => 'handovers']) }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Arsipkan Dokumen
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
