<x-app-layout>
    <x-slot name="header">Generate QR Code</x-slot>

    <!-- Selection Form for Lanyard Printing -->
    <form action="{{ route('cetak-lanyard.index') }}" method="GET" target="_blank">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-gray-800">Generate QR & Cetak Lanyard</h3>
            <button type="submit" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
                Cetak Lanyard Terpilih
            </button>
        </div>

        <!-- Table Card -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-12 text-center">
                                <input type="checkbox" id="selectAll" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                            </th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-36 text-center">QR Code</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-40 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswa as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-center">
                                    <input type="checkbox" name="ids[]" value="{{ $item->id }}" class="siswa-checkbox rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $item->nis }}</td>
                                <td class="px-6 py-4 text-gray-900 font-semibold">{{ $item->nama_siswa }}</td>
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-center">
                                        <div class="p-1 bg-white border border-gray-100 rounded-lg shadow-sm flex items-center justify-center">
                                            <img src="{{ $item->qr_base64 }}" class="w-10 h-10 object-contain" alt="QR Code">
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('generate-qr.download', $item->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 hover:bg-blue-600 text-blue-600 hover:text-white text-xs font-semibold rounded-lg transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                                        Download QR
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <p class="font-medium">Belum ada data murid</p>
                                    <p class="text-xs mt-1">Tambahkan data murid terlebih dahulu untuk generate QR.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.siswa-checkbox');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
    @endpush
</x-app-layout>
