<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Lanyard - AbsenQR</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f9fafb;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background-color: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .card-page {
                page-break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #e5e7eb !important;
            }
        }
    </style>
</head>
<body class="p-8 antialiased">
    <!-- Top Action Bar (hidden on print) -->
    <div class="no-print max-w-5xl mx-auto flex items-center justify-between mb-8 p-4 bg-white rounded-2xl shadow-sm border border-gray-100">
        <div class="flex items-center gap-3">
            <a href="{{ route('generate-qr.index') }}" class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-xl transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                Kembali
            </a>
            <h1 class="text-base font-bold text-gray-800">Preview Cetak Lanyard</h1>
        </div>
        <button onclick="window.print()" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            Cetak Lanyard
        </button>
    </div>

    <!-- Lanyard Cards Container -->
    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($siswa as $item)
            <div class="card-page bg-white w-[300px] h-[450px] mx-auto border border-gray-100 rounded-[24px] shadow-sm flex flex-col justify-between overflow-hidden relative border-t-8 border-t-blue-600 bg-gradient-to-b from-blue-50/20 via-white to-white">
                <!-- Top Header -->
                <div class="pt-6 px-6 text-center">
                    <span class="text-[9px] font-bold text-blue-600 tracking-widest uppercase">Absensi Siswa</span>
                    <h2 class="text-xs font-bold text-gray-800 uppercase tracking-tight mt-0.5">SMK NEGERI 1 JAKARTA</h2>
                </div>

                <!-- Mid QR Code Section -->
                <div class="flex flex-col items-center justify-center py-4">
                    <div class="p-3 bg-white border border-gray-100 rounded-2xl shadow-sm mb-2 flex items-center justify-center">
                        <img src="{{ $item->qr_base64 }}" class="w-[130px] h-[130px] object-contain" alt="QR Code">
                    </div>
                    <span class="font-mono text-[10px] font-bold text-gray-400 tracking-wider">NIS: {{ $item->nis }}</span>
                </div>

                <!-- Student Detail Footer -->
                <div class="bg-gray-50 border-t border-gray-100 p-6 text-center flex flex-col items-center">
                    <h3 class="text-sm font-bold text-gray-900 leading-tight truncate max-w-[250px]">{{ $item->nama_siswa }}</h3>
                    <p class="text-[11px] font-semibold text-blue-600 mt-1 uppercase tracking-wide">KELAS {{ $item->kelas->nama_kelas ?? '-' }}</p>
                    <span class="mt-3 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9px] font-bold bg-green-50 text-green-700 border border-green-200">
                        <span class="w-1 h-1 rounded-full bg-green-500"></span>
                        KARTU AKTIF
                    </span>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12 text-gray-400">
                Belum ada data siswa yang terpilih.
            </div>
        @endforelse
    </div>
</body>
</html>
