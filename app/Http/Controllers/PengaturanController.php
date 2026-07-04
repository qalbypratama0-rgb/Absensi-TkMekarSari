<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use Illuminate\Http\Request;

class PengaturanController extends Controller
{
    public function index()
    {
        $batasJam = Pengaturan::where('key', 'batas_jam_terlambat')->first();
        $waktuTerlambat = $batasJam ? $batasJam->value : '07:15';

        return view('pengaturan.index', compact('waktuTerlambat'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'waktu_terlambat' => 'required|date_format:H:i',
        ]);

        Pengaturan::updateOrCreate(
            ['key' => 'batas_jam_terlambat'],
            ['value' => $request->waktu_terlambat . ':00']
        );

        return redirect()->route('pengaturan.index')->with('success', 'Batas jam terlambat berhasil diperbarui.');
    }
}
