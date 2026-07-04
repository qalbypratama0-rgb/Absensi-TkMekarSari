<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        // Default: hari ini
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
        $kelasId = $request->input('kelas_id');

        // Query absensi dengan filter
        $query = Absensi::with(['siswa', 'siswa.kelas'])
            ->whereDate('waktu_hadir', '>=', $tanggalMulai)
            ->whereDate('waktu_hadir', '<=', $tanggalSelesai);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $absensi = $query->orderBy('waktu_hadir', 'desc')->get();

        // Hitung statistik
        $totalHadir = $absensi->where('status', 'Hadir')->count();
        $totalTerlambat = $absensi->where('status', 'Terlambat')->count();
        $totalSakit = $absensi->where('status', 'Sakit')->count();
        $totalIzin = $absensi->where('status', 'Izin')->count();
        $totalAlpha = $absensi->where('status', 'Alpha')->count();
        $totalSemua = $absensi->count();

        // Data kelas untuk dropdown filter
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        return view('laporan.index', compact(
            'absensi',
            'totalHadir',
            'totalTerlambat',
            'totalSakit',
            'totalIzin',
            'totalAlpha',
            'totalSemua',
            'kelasList',
            'tanggalMulai',
            'tanggalSelesai',
            'kelasId'
        ));
    }

    public function updateStatus(Request $request, Absensi $absensi)
    {
        $request->validate([
            'status' => 'required|in:Hadir,Terlambat,Sakit,Izin,Alpha,Hadir Manual',
        ]);

        $absensi->update(['status' => $request->status]);

        // Return JSON for AJAX requests
        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Status berhasil diubah',
                'status'  => $request->status,
            ]);
        }

        return back()->with('success', 'Status absensi berhasil diperbarui.');
    }

    public function export(Request $request)
    {
        $tanggalMulai = $request->input('tanggal_mulai', Carbon::today()->toDateString());
        $tanggalSelesai = $request->input('tanggal_selesai', Carbon::today()->toDateString());
        $kelasId = $request->input('kelas_id');

        $query = Absensi::with(['siswa', 'siswa.kelas'])
            ->whereDate('waktu_hadir', '>=', $tanggalMulai)
            ->whereDate('waktu_hadir', '<=', $tanggalSelesai);

        if ($kelasId) {
            $query->whereHas('siswa', function ($q) use ($kelasId) {
                $q->where('kelas_id', $kelasId);
            });
        }

        $absensi = $query->orderBy('waktu_hadir', 'desc')->get();

        $filename = 'laporan_absensi_' . $tanggalMulai . '_' . $tanggalSelesai . '.csv';

        $response = new StreamedResponse(function () use ($absensi) {
            $handle = fopen('php://output', 'w');

            // BOM for Excel UTF-8 compatibility
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Header
            fputcsv($handle, ['No', 'Waktu Scan', 'NIS', 'Nama Siswa', 'Kelas', 'Status']);

            // Data
            foreach ($absensi as $index => $item) {
                fputcsv($handle, [
                    $index + 1,
                    $item->waktu_hadir->format('d/m/Y H:i:s'),
                    $item->siswa->nis ?? '-',
                    $item->siswa->nama_siswa ?? '-',
                    $item->siswa->kelas->nama_kelas ?? '-',
                    $item->status,
                ]);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');

        return $response;
    }
}
