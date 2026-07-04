<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\Absensi;
use Carbon\Carbon;

class AutoAlphaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'absen:auto-alpha';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menandai siswa yang tidak absen hari ini dengan status Alpha';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();

        // Get student IDs who already checked in today
        $sudahAbsenIds = Absensi::whereDate('waktu_hadir', $today)
            ->pluck('siswa_id')
            ->toArray();

        // Get students who have not checked in today
        $tidakAbsenSiswa = Siswa::whereNotIn('id', $sudahAbsenIds)->get();

        $count = 0;
        foreach ($tidakAbsenSiswa as $siswa) {
            Absensi::create([
                'siswa_id'    => $siswa->id,
                'waktu_hadir' => Carbon::today()->setTime(15, 0, 0),
                'status'      => 'Alpha',
            ]);
            $count++;
        }

        $this->info("Berhasil menandai {$count} siswa dengan status Alpha.");
    }
}
