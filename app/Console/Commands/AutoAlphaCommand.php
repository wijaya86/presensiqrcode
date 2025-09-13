<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Siswa;
use App\Models\Absensi;
use App\Models\AutoAlphaLog;
use Carbon\Carbon;

class AutoAlphaCommand extends Command
{
    protected $signature = 'absensi:alpha';
    protected $description = 'Menandai siswa Alpha jika belum absen sampai jam tertentu (diatur di .env)';

    public function handle()
    {
        $hariIni = Carbon::today();
        $jamSekarang = Carbon::now();

        // Ambil deadline dari .env, default jam 22:00 jika tidak ada
        $deadlineJam = env('AUTO_ALPHA_DEADLINE', '22:00');
        [$hour, $minute] = explode(':', $deadlineJam);

        $deadline = Carbon::today()->setHour($hour)->setMinute($minute)->setSecond(0);

        $this->info("=== AutoAlphaCommand dijalankan ===");
        $this->info("Tanggal: {$hariIni->toDateString()} | Sekarang: {$jamSekarang->format('H:i:s')} | Deadline: {$deadline->format('H:i:s')}");

        $siswaList = Siswa::all();

        foreach ($siswaList as $siswa) {
            $this->line("Cek NISN: {$siswa->NISN}");

            $sudahAbsen = Absensi::where('NISN', $siswa->NISN)
                ->whereDate('tanggal', $hariIni)
                ->exists();

            if ($sudahAbsen) {
                $this->line("➡️  Siswa {$siswa->NISN} sudah ada absensi, skip.");
                continue;
            }

            if ($jamSekarang->greaterThanOrEqualTo($deadline)) {
                // Buat absensi hanya jika belum ada
                Absensi::firstOrCreate([
                    'NISN'        => $siswa->NISN,
                    'tanggal'     => $hariIni,
                ], [
                    'id_Kehadiran'=> 2, // Alpha
                ]);

                // Buat log hanya jika belum ada
                AutoAlphaLog::firstOrCreate([
                    'nisn'    => $siswa->NISN,
                    'tanggal' => $hariIni,
                ], [
                    'status'  => 'Alpha',
                    'pesan'   => "Siswa tidak hadir sampai deadline ({$deadlineJam}), otomatis ditandai Alpha."
                ]);

                $this->warn("⚠️  Siswa {$siswa->NISN} otomatis ditandai Alpha.");
            } else {
                $this->line("⏳ Belum lewat deadline ({$deadlineJam}), belum ditandai Alpha.");
            }
        }

        $this->info("=== Proses auto Alpha selesai ===");
    }
}
