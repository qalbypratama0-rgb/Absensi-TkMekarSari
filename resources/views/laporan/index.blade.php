<x-app-layout>
    <x-slot name="header">Laporan Absensi</x-slot>

    {{-- Filter Card --}}
    <div class="bg-white shadow-sm rounded-xl p-6 mb-6 border border-gray-100">
        <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ $tanggalMulai }}"
                    class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Tanggal Selesai</label>
                <input type="date" name="tanggal_selesai" value="{{ $tanggalSelesai }}"
                    class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[180px]">
                <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kelas</label>
                <select name="kelas_id"
                    class="w-full rounded-lg border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">Semua Kelas</option>
                    @foreach($kelasList as $kelas)
                        <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->nama_kelas }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center gap-2">
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    Terapkan
                </button>
                <button type="button" onclick="window.print()"
                    class="px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold rounded-lg transition-colors">
                    <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                    Print
                </button>
                <a href="{{ route('laporan.export', request()->query()) }}"
                    class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
                    <svg class="w-4 h-4 inline -mt-0.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    Export Excel
                </a>
            </div>
        </form>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Hadir</div>
            <div class="text-2xl font-bold text-green-600">{{ $totalHadir }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Terlambat</div>
            <div class="text-2xl font-bold text-amber-500">{{ $totalTerlambat }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Sakit</div>
            <div class="text-2xl font-bold text-blue-500">{{ $totalSakit }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Izin</div>
            <div class="text-2xl font-bold text-purple-500">{{ $totalIzin }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Alpha</div>
            <div class="text-2xl font-bold text-red-600">{{ $totalAlpha }}</div>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-1">Total</div>
            <div class="text-2xl font-bold text-gray-800">{{ $totalSemua }}</div>
        </div>
    </div>

    {{-- Tabel Data --}}
    <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 text-left">
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">No</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Waktu Scan</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">NIS</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Siswa</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Kelas</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3.5 font-semibold text-gray-500 text-xs uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($absensi as $index => $item)
                        <tr class="hover:bg-gray-50/50 transition-colors"
                            x-data="{
                                status: '{{ $item->status }}',
                                saving: false,
                                showAlert: false,
                                alertMessage: '',
                                async submitStatus(e) {
                                    this.saving = true;
                                    try {
                                        const res = await fetch('{{ route('laporan.updateStatus', $item->id) }}', {
                                            method: 'PATCH',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                                'Accept': 'application/json'
                                            },
                                            body: JSON.stringify({ status: this.status })
                                        });
                                        const data = await res.json();
                                        if (data.success) {
                                            this.alertMessage = data.message;
                                            this.showAlert = true;
                                            setTimeout(() => this.showAlert = false, 2500);
                                        }
                                    } catch(err) {
                                        console.error(err);
                                    }
                                    this.saving = false;
                                }
                            }">
                            <td class="px-5 py-3 text-gray-600">{{ $index + 1 }}</td>
                            <td class="px-5 py-3 text-gray-600 whitespace-nowrap">{{ $item->waktu_hadir->format('d/m/Y H:i:s') }}</td>
                            <td class="px-5 py-3 text-gray-700 font-medium">{{ $item->siswa->nis ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-900 font-medium">{{ $item->siswa->nama_siswa ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $item->siswa->kelas->nama_kelas ?? '-' }}</td>
                            <td class="px-5 py-3">
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
                            <td class="px-5 py-3">
                                <form @submit.prevent="submitStatus" class="flex items-center gap-2">
                                    <select x-model="status"
                                        class="rounded-lg border-gray-200 text-xs py-1.5 px-2 focus:border-blue-500 focus:ring-blue-500">
                                        @foreach(['Hadir', 'Terlambat', 'Sakit', 'Izin', 'Alpha', 'Hadir Manual'] as $s)
                                            <option value="{{ $s }}">{{ $s }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" :disabled="saving"
                                        class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors shadow-sm disabled:opacity-50">
                                        <span x-show="!saving">Simpan</span>
                                        <span x-show="saving" x-cloak>...</span>
                                    </button>
                                    <span x-show="showAlert" x-transition
                                        class="text-[10px] font-semibold text-green-700 bg-green-50 border border-green-200 px-2 py-1 rounded-lg" x-cloak
                                        x-text="alertMessage"></span>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                <p class="font-medium">Belum ada data absensi</p>
                                <p class="text-xs mt-1">Sesuaikan filter tanggal atau kelas untuk melihat data.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
