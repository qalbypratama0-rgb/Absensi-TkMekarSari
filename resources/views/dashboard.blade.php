<x-app-layout>
    <x-slot name="header">Dashboard</x-slot>

    <!-- Greeting -->
    <div class="mb-8">
        <h1 class="text-xl font-bold text-gray-900">Selamat Datang, {{ Auth::user()->name }} 👋</h1>
        <p class="text-sm text-gray-500 mt-1">{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mb-8">
        <!-- Total Siswa -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-3 right-3 w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
            </div>
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Total Siswa</div>
            <div class="text-3xl font-bold text-blue-600">{{ $totalSiswa }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Terdaftar</div>
        </div>

        <!-- Hadir -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-3 right-3 w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Hadir</div>
            <div class="text-3xl font-bold text-green-600">{{ $totalHadir }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Hari ini</div>
        </div>

        <!-- Terlambat -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-3 right-3 w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
            </div>
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Terlambat</div>
            <div class="text-3xl font-bold text-amber-500">{{ $totalTerlambat }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Hari ini</div>
        </div>

        <!-- Izin / Sakit -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-3 right-3 w-10 h-10 bg-purple-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
            </div>
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Izin/Sakit</div>
            <div class="text-3xl font-bold text-purple-500">{{ $totalIzinSakit }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Hari ini</div>
        </div>

        <!-- Alpha -->
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 relative overflow-hidden">
            <div class="absolute top-3 right-3 w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" /></svg>
            </div>
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Alpha</div>
            <div class="text-3xl font-bold text-red-600">{{ $totalAlpha }}</div>
            <div class="text-[10px] text-gray-400 mt-1">Hari ini</div>
        </div>
    </div>

    <!-- Recent Activity Table -->
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-gray-800">Aktivitas Absensi Terbaru Hari Ini</h3>
            <a href="{{ route('laporan.index') }}" class="text-xs text-blue-600 hover:text-blue-700 font-semibold transition-colors">Lihat Semua →</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Waktu</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">NIS</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Kelas</th>
                        <th class="px-6 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($riwayatTerakhir as $item)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-6 py-3.5 text-gray-500 font-mono text-xs whitespace-nowrap">{{ $item->waktu_hadir->format('H:i:s') }}</td>
                            <td class="px-6 py-3.5 font-mono text-xs text-gray-500">{{ $item->siswa->nis ?? '-' }}</td>
                            <td class="px-6 py-3.5 text-gray-900 font-semibold">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                            <td class="px-6 py-3.5 text-gray-600">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-6 py-3.5">
                                @php
                                    $badgeColors = [
                                        'Hadir' => 'bg-green-50 text-green-700 border-green-200',
                                        'Terlambat' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'Sakit' => 'bg-blue-50 text-blue-700 border-blue-200',
                                        'Izin' => 'bg-purple-50 text-purple-700 border-purple-200',
                                        'Alpha' => 'bg-red-50 text-red-700 border-red-200',
                                        'Hadir Manual' => 'bg-teal-50 text-teal-700 border-teal-200',
                                    ];
                                    $color = $badgeColors[$item->status] ?? 'bg-gray-50 text-gray-700 border-gray-200';
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $color }}">
                                    {{ $item->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M4 8h16M4 16h16" /></svg>
                                <p class="font-medium">Belum ada aktivitas absensi hari ini</p>
                                <p class="text-xs mt-1">Buka Scanner untuk mulai memindai QR Code siswa.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
