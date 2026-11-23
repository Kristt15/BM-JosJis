<x-layouts.app title="Tambah Pertemuan Baru">
    <div class="max-w-2xl mx-auto">
        <!-- Breadcrumb -->
        <div class="mb-6">
            <a href="{{ route('activities.index') }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Daftar Aktivitas</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="mb-6 pb-6 border-b border-gray-100">
                <h1 class="text-xl font-bold text-gray-900">Jadwalkan Pertemuan Baru</h1>
                <p class="text-sm text-gray-500 mt-1">Tambahkan jadwal kegiatan belajar-mengajar atau agenda khusus di SRK.</p>
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

            <form method="POST" action="{{ route('activities.events.store') }}" class="space-y-5">
                @csrf

                <!-- Pilih Batch -->
                <div>
                    <label for="batch_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih Batch Kegiatan <span class="text-red-500">*</span>
                    </label>
                    <select id="batch_id" name="batch_id" required
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <option value="">-- Pilih Batch --</option>
                        @foreach($batches as $batch)
                            <option value="{{ $batch->id }}" {{ old('batch_id') == $batch->id ? 'selected' : '' }}>
                                {{ $batch->name }} ({{ $batch->phase?->program?->name ?? 'Program' }} - {{ $batch->phase?->name ?? '' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nama Pertemuan & Tipe -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Kegiatan / Pertemuan <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Contoh: Pertemuan 5"
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 mb-1">
                            Tipe Kegiatan <span class="text-red-500">*</span>
                        </label>
                        <select id="type" name="type" required
                                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            @foreach($types as $type)
                                <option value="{{ $type->value }}" {{ old('type') == $type->value ? 'selected' : '' }}>
                                    {{ $type->label() }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Tanggal & Jam -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="event_date" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Pertemuan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" id="event_date" name="event_date" value="{{ old('event_date', date('Y-m-d')) }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label for="event_time" class="block text-sm font-medium text-gray-700 mb-1">
                            Waktu Mulai <span class="text-red-500">*</span>
                        </label>
                        <input type="time" id="event_time" name="event_time" value="{{ old('event_time', '08:00') }}" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                </div>

                <!-- Lokasi -->
                <div>
                    <label for="location" class="block text-sm font-medium text-gray-700 mb-1">
                        Lokasi Pelaksanaan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="location" name="location" value="{{ old('location', 'Sekolah Rakyat Kejawan') }}" required
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <!-- Deskripsi / Agenda Materi -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Rencana Materi / Deskripsi Kegiatan
                    </label>
                    <textarea id="description" name="description" rows="3"
                              placeholder="Contoh: Pengenalan dasar berhitung matematika dan membaca cerita moral bersama anak-anak SRK."
                              class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('description') }}</textarea>
                </div>

                <!-- Tombol Submit -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('activities.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition">
                        Simpan Pertemuan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
