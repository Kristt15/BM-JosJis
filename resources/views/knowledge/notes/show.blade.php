<x-layouts.app title="{{ $teachingNote->title }}">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('knowledge.index', ['tab' => 'notes']) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Knowledge Hub</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <!-- Header Note -->
            <div class="border-b border-gray-100 pb-6 mb-6">
                <div class="flex items-center gap-2 text-xs font-semibold text-blue-600 uppercase tracking-wider mb-2">
                    <span>{{ $teachingNote->event?->name }}</span>
                    <span>•</span>
                    <span>{{ $teachingNote->event?->batch?->name }}</span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $teachingNote->title }}</h1>

                <div class="flex items-center justify-between text-xs text-gray-500 mt-4 pt-4 border-t border-gray-50">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 font-bold flex items-center justify-center">
                            {{ strtoupper(substr($teachingNote->volunteer?->user?->name ?? 'V', 0, 1)) }}
                        </div>
                        <span>Ditulis oleh: <strong>{{ $teachingNote->volunteer?->user?->name ?? 'Relawan BM' }}</strong></span>
                    </div>
                    <span>{{ $teachingNote->created_at->format('d F Y, H:i') }} WIB</span>
                </div>
            </div>

            <!-- Media Ajar -->
            @if($teachingNote->materials_used)
                <div class="mb-6 bg-blue-50 border border-blue-100 p-4 rounded-xl">
                    <span class="text-xs font-bold text-blue-800 uppercase tracking-wider">📦 Media & Alat yang Digunakan:</span>
                    <p class="text-sm text-blue-900 mt-1 font-medium">{{ $teachingNote->materials_used }}</p>
                </div>
            @endif

            <!-- Isi Jurnal -->
            <div class="prose max-w-none text-gray-700 leading-relaxed whitespace-pre-line text-sm md:text-base">
                {{ $teachingNote->content }}
            </div>
        </div>
    </div>
</x-layouts.app>
