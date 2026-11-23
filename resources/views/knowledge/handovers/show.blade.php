<x-layouts.app title="{{ $handover->title }}">
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('knowledge.index', ['tab' => 'handovers']) }}" class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
                <span>←</span>
                <span>Kembali ke Knowledge Hub</span>
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:p-8">
            <div class="border-b border-gray-100 pb-6 mb-6">
                <div class="flex items-center gap-2 mb-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800 uppercase tracking-wider">
                        Handover {{ $handover->type->label() }}
                    </span>
                    <span class="text-xs text-gray-500 font-medium">
                        {{ $handover->program?->name }} (Periode {{ $handover->program?->year }})
                    </span>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $handover->title }}</h1>

                <div class="flex items-center justify-between text-xs text-gray-500 mt-4 pt-4 border-t border-gray-50">
                    <span>Diarsipkan oleh: <strong>{{ $handover->creator?->name ?? 'Pengurus BM' }}</strong></span>
                    <span>Tanggal: {{ $handover->created_at->format('d F Y') }}</span>
                </div>
            </div>

            <!-- Isi Dokumen Handover -->
            <div class="prose max-w-none text-gray-800 leading-relaxed whitespace-pre-line text-sm md:text-base bg-gray-50 p-6 rounded-xl border border-gray-100 font-sans">
                {{ $handover->content }}
            </div>
        </div>
    </div>
</x-layouts.app>
