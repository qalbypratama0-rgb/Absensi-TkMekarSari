<x-app-layout>
    <x-slot name="header">Data Kelas</x-slot>

    <div x-data="{ 
        showCreateModal: false, 
        showEditModal: false,
        editId: '',
        editNama: '',
        openEditModal(id, nama) {
            this.editId = id;
            this.editNama = nama;
            this.showEditModal = true;
        }
    }">
        <!-- Top bar with Add Button -->
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-gray-800">Daftar Kelas</h3>
            <button @click="showCreateModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m6-6H6" /></svg>
                Tambah Kelas
            </button>
        </div>

        {{-- Error/Success Notification --}}
        @if(session('error'))
            <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 px-4 py-3 rounded-xl bg-red-50 border border-red-200 text-red-700 text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Table Card -->
        <div class="bg-white shadow-sm rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-gray-50 text-left">
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-16">No</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Kelas</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-40 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($kelas as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 text-gray-900 font-semibold">{{ $item->nama_kelas }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal('{{ $item->id }}', '{{ $item->nama_kelas }}')" class="px-3 py-1.5 bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 text-xs font-semibold rounded-lg transition-colors">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('kelas.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="px-3 py-1.5 bg-gray-50 hover:bg-red-50 text-gray-600 hover:text-red-600 text-xs font-semibold rounded-lg transition-colors">
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                    <p class="font-medium">Belum ada data kelas</p>
                                    <p class="text-xs mt-1">Klik "Tambah Kelas" untuk menambahkan data kelas baru.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Add Modal -->
        <div x-show="showCreateModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showCreateModal = false" class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-base font-bold text-gray-900">Tambah Kelas</h4>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('kelas.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Kelas</label>
                        <input type="text" name="nama_kelas" required placeholder="Contoh: X RPL 1" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showCreateModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div x-show="showEditModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showEditModal = false" class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-base font-bold text-gray-900">Edit Kelas</h4>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" :action="`{{ route('kelas.index') }}/${editId}`" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Kelas</label>
                        <input type="text" name="nama_kelas" :value="editNama" @input="editNama = $event.target.value" required placeholder="Contoh: X RPL 1" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
