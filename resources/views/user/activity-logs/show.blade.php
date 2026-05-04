@extends('layouts.user.index')

@section('title', 'Pilih Perlengkapan')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Detail Aktivitas</h1>
                    <p class="text-gray-600 mt-2">Informasi lengkap tentang aktivitas ini</p>
                </div>
                <a href="{{ route('user.activity-logs.index') }}" 
                   class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>

        <!-- Main Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            
            <!-- Header Info -->
            <div class="bg-gradient-to-r from-[#000DFB] to-[#0000cc] px-6 py-4">
                <div class="flex items-center justify-between text-white">
                    <div>
                        <span class="text-sm opacity-90">ID Log: #{{ $activity->id }}</span>
                        <h2 class="text-xl font-semibold mt-1">{{ ucfirst(str_replace('_', ' ', $activity->action)) }}</h2>
                    </div>
                    <div class="text-right">
                        <div class="text-sm opacity-90">{{ $activity->created_at->format('l, d F Y') }}</div>
                        <div class="text-xs opacity-75">{{ $activity->created_at->format('H:i:s') }}</div>
                    </div>
                </div>
            </div>

            <!-- Content -->
            <div class="p-6 space-y-6">
                <!-- Informasi Dasar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</label>
                        <p class="text-gray-900 mt-1 font-medium">{{ $activity->description }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</label>
                        <p class="text-gray-900 mt-1 text-sm break-all">{{ $activity->user_agent ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</label>
                        <p class="text-gray-900 mt-1 font-mono text-sm">{{ $activity->ip_address ?? '-' }}</p>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-4">
                        <label class="text-xs font-medium text-gray-500 uppercase tracking-wider">Model Terkait</label>
                        <p class="text-gray-900 mt-1">
                            {{ class_basename($activity->model_type) ?? '-' }}
                            @if($activity->model_id)
                            <span class="text-xs text-gray-500">(ID: {{ $activity->model_id }})</span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Data Perubahan (jika ada) -->
                @if($activity->old_data || $activity->new_data)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Perubahan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if($activity->old_data)
                        <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                            <label class="text-xs font-medium text-red-700 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                Data Sebelumnya
                            </label>
                            <pre class="mt-2 text-xs text-gray-700 overflow-x-auto">{{ json_encode($activity->old_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif

                        @if($activity->new_data)
                        <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                            <label class="text-xs font-medium text-green-700 uppercase tracking-wider flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Data Baru
                            </label>
                            <pre class="mt-2 text-xs text-gray-700 overflow-x-auto">{{ json_encode($activity->new_data, JSON_PRETTY_PRINT) }}</pre>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Related Data (jika ada) -->
                @if($relatedData)
                <div class="border-t border-gray-200 pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Terkait</h3>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <pre class="text-xs text-gray-700 overflow-x-auto">{{ json_encode($relatedData->toArray(), JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
                @endif

                <!-- Tombol Hapus -->
                <div class="border-t border-gray-200 pt-6 flex justify-end">
                    <button onclick="confirmDelete({{ $activity->id }})" 
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        Hapus Log Ini
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(id) {
    if (confirm('Apakah Anda yakin ingin menghapus log aktivitas ini?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `{{ route('user.activity-logs.destroy', '') }}/${id}`;
        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection