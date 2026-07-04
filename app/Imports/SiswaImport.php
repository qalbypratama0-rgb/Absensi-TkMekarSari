<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\Kelas;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SiswaImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Skip if vital fields are empty
        if (empty($row['nis']) || empty($row['nama_siswa']) || empty($row['jk']) || empty($row['nama_kelas'])) {
            return null;
        }

        // Find or create Kelas
        $kelas = Kelas::firstOrCreate([
            'nama_kelas' => trim($row['nama_kelas'])
        ]);

        // Standardize gender input (Laki-laki / Perempuan to L / P)
        $jk = strtoupper(trim($row['jk']));
        if (str_starts_with($jk, 'L')) {
            $jk = 'L';
        } elseif (str_starts_with($jk, 'P')) {
            $jk = 'P';
        }

        // updateOrCreate to prevent unique key exception and sync records
        return Siswa::updateOrCreate(
            ['nis' => trim($row['nis'])],
            [
                'nama_siswa' => trim($row['nama_siswa']),
                'jk'         => $jk,
                'kelas_id'   => $kelas->id,
            ]
        );
    }
}
