@extends('layouts.user.index')

@section('title', 'Pilih Perlengkapan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Log Aktivitas</h1>
            <p class="text-gray-600 mt-2">Riwayat semua aktivitas Anda di sistem</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Total Aktivitas</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['total']) }}</p>
                    </div>
                    <div class="bg-[#000DFB]/10 rounded-full p-3">
                        <svg class="w-6 h-6 text-[#000DFB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Bulan Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['this_month']) }}</p>
                    </div>
                    <div class="bg-[#000DFB]/10 rounded-full p-3">
                        <svg class="w-6 h-6 text-[#000DFB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 font-medium">Hari Ini</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($stats['today']) }}</p>
                    </div>
                    <div class="bg-[#000DFB]/10 rounded-full p-3">
                        <svg class="w-6 h-6 text-[#000DFB]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters & Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 mb-6">
            <form method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Aksi, deskripsi, atau IP..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#000DFB] focus:border-[#000DFB]">
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Aksi</label>
                    <select name="action" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#000DFB] focus:border-[#000DFB]">
                        <option value="all">Semua Aksi</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#000DFB] focus:border-[#000DFB]">
                </div>

                <div class="w-48">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-[#000DFB] focus:border-[#000DFB]">
                </div>

                <div class="flex gap-2">
                    <button type="submit" class="px-6 py-2 bg-[#000DFB] text-white rounded-lg hover:bg-[#0000cc] transition-colors font-medium">
                        Filter
                    </button>
                    <a href="{{ route('user.activity-logs.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium">
                        Reset
                    </a>
                    <a href="{{ route('user.activity-logs.export', request()->query()) }}" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        Export CSV
                    </a>
                    <button type="button" onclick="confirmClearAll()" class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Hapus Semua
                    </button>
                </div>
            </form>
        </div>

        <!-- Activities Table -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($activities as $index => $activity)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activities->firstItem() + $index }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div>{{ $activity->created_at->format('d/m/Y') }}</div>
                                <div class="text-xs text-gray-500">{{ $activity->created_at->format('H:i:s') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-medium rounded-full 
                                    @if(str_contains($activity->action, 'created')) bg-green-100 text-green-800
                                    @elseif(str_contains($activity->action, 'updated')) bg-blue-100 text-blue-800
                                    @elseif(str_contains($activity->action, 'deleted')) bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $activity->action)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-md">
                                <div class="truncate">{{ $activity->description }}</div>
                                @if($activity->model_type)
                                <div class="text-xs text-gray-500 mt-1">
                                    ID: {{ $activity->model_id }}
                                </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $activity->ip_address ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <a href="{{ route('user.activity-logs.show', $activity->id) }}" 
                                   class="text-[#000DFB] hover:text-[#0000cc] font-medium mr-3">
                                    Detail
                                </a>
                                <button onclick="confirmDelete({{ $activity->id }})" 
                                        class="text-red-600 hover:text-red-800 font-medium">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <p class="mt-2">Belum ada aktivitas yang tercatat</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus log aktivitas ini?')) {
        // Cara 1: Menggunakan form dengan action langsung
        var form = document.getElementById('delete-form-' + id);
        if (!form) {
            form = document.createElement('form');
            form.id = 'delete-form-' + id;
            form.method = 'POST';
            form.action = '/user/activity-logs/' + id;
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
        }
        form.submit();
        
        // Cara 2 (alternatif) - Menggunakan fetch API
        /*
        fetch('/user/activity-logs/' + id, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            }
        }).then(response => {
            if (response.ok) {
                location.reload();
            }
        });
        */
    }
}

function confirmClearAll() {
    if (confirm('PERINGATAN: Semua log aktivitas akan dihapus permanen. Lanjutkan?')) {
        var form = document.getElementById('clear-all-form');
        if (!form) {
            form = document.createElement('form');
            form.id = 'clear-all-form';
            form.method = 'POST';
            form.action = '{{ route("user.activity-logs.clear-all") }}';
            form.innerHTML = '@csrf @method("DELETE")';
            document.body.appendChild(form);
        }
        form.submit();
    }
}
</script>
@endsection