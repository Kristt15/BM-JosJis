<x-layouts.app title="Edit Volunteer">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('volunteers.index') }}" class="hover:text-blue-600 transition">Volunteer</a>
        <span>/</span>
        <a href="{{ route('volunteers.show', $volunteer) }}" class="hover:text-blue-600 transition">{{ $volunteer->name }}</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">Edit</span>
    </nav>

    <div class="max-w-2xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Edit Volunteer</h1>
            <p class="text-sm text-gray-500 mt-1">Perbarui data {{ $volunteer->name }}</p>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <form method="POST" action="{{ route('volunteers.update', $volunteer) }}">
                @csrf
                @method('PUT')

                <!-- Informasi Akun -->
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b">Informasi Akun</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name', $volunteer->name) }}"
                                   class="w-full border @error('name') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email', $volunteer->user?->email) }}"
                                   class="w-full border @error('email') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Informasi Akademik -->
                <div class="mb-6">
                    <h2 class="text-sm font-semibold text-gray-700 uppercase tracking-wider mb-4 pb-2 border-b">Informasi Akademik</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                NIM <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="nim" value="{{ old('nim', $volunteer->nim) }}"
                                   class="w-full border @error('nim') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('nim')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Angkatan <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="angkatan" value="{{ old('angkatan', $volunteer->generation) }}"
                                   min="2010" max="2030"
                                   class="w-full border @error('angkatan') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('angkatan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Jurusan <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="jurusan" value="{{ old('jurusan', $volunteer->major) }}"
                                   class="w-full border @error('jurusan') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('jurusan')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon</label>
                            <input type="text" name="phone" value="{{ old('phone', $volunteer->phone) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status"
                                    class="w-full border @error('status') border-red-400 @else border-gray-300 @enderror rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="active" {{ old('status', $volunteer->status->value) === 'active' ? 'selected' : '' }}>Aktif</option>
                                <option value="alumni" {{ old('status', $volunteer->status->value) === 'alumni' ? 'selected' : '' }}>Alumni</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex items-center justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('volunteers.show', $volunteer) }}"
                       class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>
