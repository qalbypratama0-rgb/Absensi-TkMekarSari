<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Siswa;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $totalSiswa = Siswa::count();

        $totalHadir = Absensi::whereDate('waktu_hadir', $today)
            ->whereIn('status', ['Hadir', 'Terlambat', 'Hadir Manual'])
            ->count();

        $totalTerlambat = Absensi::whereDate('waktu_hadir', $today)
            ->where('status', 'Terlambat')
            ->count();

        $totalIzinSakit = Absensi::whereDate('waktu_hadir', $today)
            ->whereIn('status', ['Izin', 'Sakit'])
            ->count();

        $totalAlpha = Absensi::whereDate('waktu_hadir', $today)
            ->where('status', 'Alpha')
            ->count();

        $riwayatTerakhir = Absensi::with(['siswa', 'siswa.kelas'])
            ->whereDate('waktu_hadir', $today)
            ->orderBy('waktu_hadir', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard', compact(
            'totalSiswa',
            'totalHadir',
            'totalTerlambat',
            'totalIzinSakit',
            'totalAlpha',
            'riwayatTerakhir'
        ));
    }
}
