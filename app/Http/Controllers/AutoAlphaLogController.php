<?php

namespace App\Http\Controllers;

use App\Models\AutoAlphaLog;
use App\Models\Kelasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AutoAlphaLogController extends Controller
{
    public function index(Request $request)
    {
        // ambil filter dari request
        $startDate = $request->input('start_date', Carbon::now()->toDateString());
        $endDate   = $request->input('end_date', Carbon::now()->toDateString());
        $kelasFilter = $request->input('kelas', '0');

        // ambil daftar kelas untuk dropdown
        $kelasList = Kelasi::orderBy('NamaKelas')
       ->whereNotIn('id', [10, 11])
        ->get();

        // query log dengan relasi siswa + kelas
        $logsQuery = AutoAlphaLog::with(['siswa.kelasi'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->latest();

        if ($kelasFilter !== "0") {
            $logsQuery->whereHas('siswa', function ($q) use ($kelasFilter) {
                $q->where('id_Kelas', $kelasFilter);
            });
        }

        $logs = $logsQuery->paginate(20);

        return view('pages/Data User/auto_alpha', compact('logs', 'kelasList', 'kelasFilter', 'startDate', 'endDate'));
    }
}
 