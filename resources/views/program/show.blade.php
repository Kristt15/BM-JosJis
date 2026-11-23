<x-layouts.app title="Detail Program">
    <!-- Breadcrumb -->
    <nav class="text-sm text-gray-500 mb-6 flex items-center gap-2">
        <a href="{{ route('programs.index') }}" class="hover:text-blue-600 transition">Program</a>
        <span>/</span>
        <span class="text-gray-900 font-medium">{{ $program->name }}</span>
    </nav>

    <!-- Program Header -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-start justify-between flex-wrap gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold text-gray-900">{{ $program->name }}</h1>
                    @php
                        $badgeClass = match($program->status) {
                            \App\Enums\ProgramStatus::ACTIVE    => 'bg-green-100 text-green-800',
                            \App\Enums\ProgramStatus::PLANNING  => 'bg-yellow-100 text-yellow-800',
                            \App\Enums\ProgramStatus::COMPLETED => 'bg-gray-100 text-gray-700',
                        };
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {{ $badgeClass }}">
                        {{ $program->status->label() }}
                    </span>
                </div>
                @if($program->description)
                    <p class="text-gray-600 text-sm max-w-2xl">{{ $program->description }}</p>
                @endif
                @if($program->start_date)
                    <p class="text-gray-400 text-xs mt-2">
                        Periode: {{ $program->start_date->format('d M Y') }}
                        @if($program->end_date) — {{ $program->end_date->format('d M Y') }} @endif
                    </p>
                @endif
            </div>
            <div class="text-right">
                <span class="text-4xl font-bold text-blue-600">{{ $program->year }}</span>
                <div class="text-xs text-gray-400 mt-1">Tahun Program</div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-6 pt-6 border-t">
            <div class="text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $program->phases->count() }}</div>
                <div class="text-xs text-gray-500 mt-1">Phase</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-indigo-600">{{ $totalBatches }}</div>
                <div class="text-xs text-gray-500 mt-1">Batch</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ $totalVolunteers }}</div>
                <div class="text-xs text-gray-500 mt-1">Volunteer</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold text-purple-600">{{ $totalChildren }}</div>
                <div class="text-xs text-gray-500 mt-1">Anak SRK</div>
            </div>
        </div>
    </div>

    <!-- Timeline Phase -->
    @forelse($program->phases->sortBy('sequence') as $phase)
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-4 overflow-hidden">
            <!-- Phase Header -->
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full bg-blue-600 text-white flex items-center justify-center text-sm font-bold">
                        {{ $phase->sequence ?? $loop->iteration }}
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">{{ $phase->name }}</h3>
                        <p class="text-xs text-gray-500">{{ $phase->type->label() }}</p>
                    </div>
                </div>
                @if($phase->start_date)
                    <div class="text-xs text-gray-400 text-right">
                        {{ $phase->start_date->format('d M Y') }}
                        @if($phase->end_date) — {{ $phase->end_date->format('d M Y') }} @endif
                    </div>
                @endif
            </div>

            <!-- Batches dalam Phase -->
            @if($phase->batches->isEmpty())
                <div class="px-6 py-4 text-sm text-gray-400 italic">Belum ada batch dalam phase ini</div>
            @else
                <div class="p-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($phase->batches as $batch)
                        <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                            <div class="font-semibold text-sm text-gray-900 mb-2">{{ $batch->name }}</div>
                            @if($batch->start_date)
                                <div class="text-xs text-gray-400 mb-3">
                                    {{ $batch->start_date->format('d M') }}
                                    @if($batch->end_date) – {{ $batch->end_date->format('d M Y') }} @endif
                                </div>
                            @endif
                            <div class="flex items-center gap-4 text-xs text-gray-600">
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $batch->volunteers->count() }} volunteer
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                                    </svg>
                                    {{ $batch->children->count() }} anak
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    {{ $batch->events->count() }} event
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @empty
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-12 text-center text-gray-400">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
            <p class="text-sm">Belum ada phase untuk program ini</p>
        </div>
    @endforelse

    <div class="mt-4">
        <a href="{{ route('programs.index') }}" class="text-sm text-blue-600 hover:text-blue-800 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Daftar Program
        </a>
    </div>
</x-layouts.app>
