<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Pengaturan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsenController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|string',
        ]);

        $nis = $request->nis;

        // Cari siswa berdasarkan NIS
        $siswa = Siswa::with('kelas')->where('nis', $nis)->first();
        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Siswa tidak ditemukan'
            ], 404);
        }

        // Proteksi double scan hari ini
        $sudahAbsen = Absensi::where('siswa_id', $siswa->id)
            ->whereDate('waktu_hadir', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absen hari ini!'
            ], 422);
        }

        // Ambil batas jam terlambat dari pengaturan
        $pengaturan = Pengaturan::where('key', 'batas_jam_terlambat')->first();
        $batasWaktu = $pengaturan ? $pengaturan->value : '07:15:00';

        // Logika Waktu
        $sekarang = Carbon::now();
        $batasWaktuCarbon = Carbon::createFromTimeString($batasWaktu);

        if ($sekarang->format('H:i:s') <= $batasWaktuCarbon->format('H:i:s')) {
            $status = 'Hadir';
        } else {
            $status = 'Terlambat';
        }

        // Simpan ke database
        Absensi::create([
            'siswa_id' => $siswa->id,
            'waktu_hadir' => $sekarang,
            'status' => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil dicatat!',
            'data' => [
                'nama' => $siswa->nama_siswa,
                'nis' => $siswa->nis,
                'kelas' => $siswa->kelas->nama_kelas ?? '-',
                'waktu' => $sekarang->format('H:i:s'),
                'status' => $status,
            ]
        ]);
    }
}
