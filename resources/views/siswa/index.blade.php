<x-app-layout>
    <x-slot name="header">Data Murid</x-slot>

    <div x-data="{ 
        showCreateModal: false, 
        showEditModal: false,
        showImportModal: false,
        editId: '',
        editNis: '',
        editNama: '',
        editJk: 'L',
        editKelasId: '',
        openEditModal(id, nis, nama, jk, kelasId) {
            this.editId = id;
            this.editNis = nis;
            this.editNama = nama;
            this.editJk = jk;
            this.editKelasId = kelasId;
            this.showEditModal = true;
        }
    }">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-base font-semibold text-gray-800">Daftar Siswa</h3>
            <div class="flex items-center gap-2">
                <button @click="showImportModal = true" type="button" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" /></svg>
                    Import Excel
                </button>
                <button @click="showCreateModal = true" class="flex items-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v12m6-6H6" /></svg>
                    Tambah Murid
                </button>
            </div>
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
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">NIS</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Nama Siswa</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Jenis Kelamin</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-4 font-semibold text-gray-500 text-xs uppercase tracking-wider w-40 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($siswa as $index => $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $index + 1 }}</td>
                                <td class="px-6 py-4 font-mono text-xs text-gray-500">{{ $item->nis }}</td>
                                <td class="px-6 py-4 text-gray-900 font-semibold">{{ $item->nama_siswa }}</td>
                                <td class="px-6 py-4 text-gray-600">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-600 border border-gray-100">
                                        {{ $item->jk === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 font-medium">{{ $item->kelas->nama_kelas ?? '-' }}</td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button @click="openEditModal('{{ $item->id }}', '{{ $item->nis }}', '{{ $item->nama_siswa }}', '{{ $item->jk }}', '{{ $item->kelas_id }}')" class="px-3 py-1.5 bg-gray-50 hover:bg-blue-50 text-gray-600 hover:text-blue-600 text-xs font-semibold rounded-lg transition-colors">
                                            Edit
                                        </button>
                                        <form method="POST" action="{{ route('siswa.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus murid ini?')">
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
                                <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                                    <p class="font-medium">Belum ada data murid</p>
                                    <p class="text-xs mt-1">Klik "Tambah Murid" untuk menambahkan data siswa baru.</p>
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
                    <h4 class="text-base font-bold text-gray-900">Tambah Murid</h4>
                    <button @click="showCreateModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('siswa.store') }}" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" required placeholder="Contoh: 202601001" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Murid</label>
                        <input type="text" name="nama_siswa" required placeholder="Contoh: John Doe" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Jenis Kelamin</label>
                        <select name="jk" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kelas</label>
                        <select name="kelas_id" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
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
                    <h4 class="text-base font-bold text-gray-900">Edit Murid</h4>
                    <button @click="showEditModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" :action="`{{ route('siswa.index') }}/${editId}`" class="p-6 space-y-4">
                    @csrf
                    @method('PATCH')
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">NIS (Nomor Induk Siswa)</label>
                        <input type="text" name="nis" :value="editNis" @input="editNis = $event.target.value" required placeholder="Contoh: 202601001" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Nama Murid</label>
                        <input type="text" name="nama_siswa" :value="editNama" @input="editNama = $event.target.value" required placeholder="Contoh: John Doe" class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Jenis Kelamin</label>
                        <select name="jk" :value="editJk" @change="editJk = $event.target.value" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">Kelas</label>
                        <select name="kelas_id" :value="editKelasId" @change="editKelasId = $event.target.value" required class="w-full rounded-xl border-gray-200 text-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kelas</option>
                            @foreach($kelas as $k)
                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showEditModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Import Modal -->
        <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4" style="display: none;">
            <div @click.away="showImportModal = false" class="bg-white rounded-2xl w-full max-w-md shadow-xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h4 class="text-base font-bold text-gray-900">Import Excel / CSV</h4>
                    <button @click="showImportModal = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <form method="POST" action="{{ route('siswa.import') }}" enctype="multipart/form-data" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1.5">File Excel / CSV</label>
                        <input type="file" name="file_excel" required accept=".xlsx,.xls,.csv" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-[10px] text-gray-400 mt-2">Maksimum ukuran file: 2MB. Format file: .xlsx, .xls, .csv</p>
                    </div>
                    
                    <div class="p-3 bg-gray-50 rounded-xl border border-gray-100">
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-wider block mb-1">Format Kolom:</span>
                        <code class="text-xs font-mono text-gray-600 block">nis | nama_siswa | jk | nama_kelas</code>
                        <a href="data:text/csv;charset=utf-8,nis,nama_siswa,jk,nama_kelas%0A202601001,John%20Doe,L,X%20RPL%201" download="template_siswa.csv" class="mt-2 text-[10px] text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                            Download Template CSV
                        </a>
                    </div>
                    
                    <div class="flex items-center justify-end gap-2 pt-2">
                        <button type="button" @click="showImportModal = false" class="px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-xl text-sm font-semibold transition-colors">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-sm font-semibold transition-colors shadow-sm">Upload & Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
