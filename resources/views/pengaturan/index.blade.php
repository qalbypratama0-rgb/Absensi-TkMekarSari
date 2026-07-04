<x-app-layout>
    <x-slot name="header">Pengaturan</x-slot>

    <div class="max-w-xl">
        <!-- Settings Card -->
        <div class="bg-white shadow-sm rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-sm font-bold text-gray-800">Konfigurasi Sistem Absensi</h3>
                <p class="text-xs text-gray-400 mt-0.5">Atur parameter waktu untuk penentuan status kehadiran otomatis.</p>
            </div>

            <form method="POST" action="{{ route('pengaturan.update') }}" class="p-6 space-y-6">
                @csrf

                <!-- Batas Jam Terlambat -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 mb-1.5">Batas Jam Terlambat</label>
                    <p class="text-[11px] text-gray-400 mb-2">Siswa yang scan QR setelah waktu ini akan otomatis dicatat sebagai <span class="font-semibold text-amber-600">Terlambat</span>.</p>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <input type="time" name="waktu_terlambat" value="{{ old('waktu_terlambat', substr($waktuTerlambat, 0, 5)) }}" required
                                class="w-48 rounded-xl border-gray-200 text-sm font-semibold focus:border-blue-500 focus:ring-blue-500 pl-10">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 font-medium">Format: HH:MM (24 jam)</span>
                    </div>
                    @error('waktu_terlambat')
                        <p class="text-xs text-red-500 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Card -->
        <div class="mt-6 bg-blue-50/50 border border-blue-100 rounded-2xl p-5">
            <div class="flex items-start gap-3">
                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <h4 class="text-xs font-bold text-blue-800">Cara Kerja</h4>
                    <p class="text-[11px] text-blue-600 mt-1 leading-relaxed">
                        Saat siswa melakukan scan QR di Kiosk Scanner, sistem akan membandingkan waktu scan dengan <strong>Batas Jam Terlambat</strong> yang dikonfigurasi di atas. 
                        Jika waktu scan ≤ batas → status <strong class="text-green-700">Hadir</strong>. 
                        Jika waktu scan &gt; batas → status <strong class="text-amber-700">Terlambat</strong>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
