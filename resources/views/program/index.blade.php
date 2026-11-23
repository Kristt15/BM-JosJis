<x-layouts.app title="Program">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Program Bidikmisi Mengajar</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar seluruh program yang telah dijalankan</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Program</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tahun</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phase</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Periode</th>
                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-100">
                @forelse($programs as $program)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $programs->firstItem() + $loop->index }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-semibold text-gray-900">{{ $program->name }}</div>
                            @if($program->description)
                                <div class="text-xs text-gray-500 mt-0.5 truncate max-w-xs">{{ $program->description }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-mono">{{ $program->year }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $program->phases_count }} phase</td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClass = match($program->status) {
                                    \App\Enums\ProgramStatus::ACTIVE    => 'bg-green-100 text-green-800',
                                    \App\Enums\ProgramStatus::PLANNING  => 'bg-yellow-100 text-yellow-800',
                                    \App\Enums\ProgramStatus::COMPLETED => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $badgeClass }}">
                                {{ $program->status->label() }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-xs text-gray-500">
                            @if($program->start_date)
                                {{ $program->start_date->format('M Y') }}
                                @if($program->end_date) — {{ $program->end_date->format('M Y') }} @endif
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('programs.show', $program) }}"
                               class="text-blue-600 hover:text-blue-800 text-xs font-medium px-2 py-1 rounded hover:bg-blue-50 transition">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-sm">Belum ada program terdaftar</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if($programs->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $programs->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
