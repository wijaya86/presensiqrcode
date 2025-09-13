<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
//import return type View
use Illuminate\View\View;
use Carbon\Carbon;
//import return type redirectResponse
use Illuminate\Http\RedirectResponse;

class MobileController extends Controller
{
     public function index(Request $request)
    {
              $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
                $endDate   = $request->get('end_date', Carbon::now()->toDateString());
                $kelasFilter = $request->get('kelas'); // dari form

                $query = DB::table('absensis')
                    ->join('kehadirans', 'absensis.id_Kehadiran', '=', 'kehadirans.id')
                    ->join('siswas', 'absensis.NISN', '=', 'siswas.NISN')
                    ->join('kelasis', 'siswas.id_Kelas', '=', 'kelasis.id')
                    ->select(
                        'absensis.tanggal',
                        'siswas.NISN',
                        'siswas.NamaSiswa',
                        'siswas.Jenkel',
                        'kelasis.NamaKelas',
                        'kehadirans.kehadiran'
                    )
                    ->orderBy('absensis.tanggal', 'asc')
                    ->whereBetween('absensis.tanggal', [$startDate, $endDate]);

                // Kalau pilih kelas tertentu (bukan semua)
                if (!empty($kelasFilter) && $kelasFilter !== "0") {
                    $query->where('kelasis.id', $kelasFilter);
                }

                $rekap = $query->get();

                // ambil daftar kelas untuk dropdown
                $kelasList = DB::table('kelasis')
                    ->whereNotIn('id', [10, 11])
                    ->get();

                return view('pages/Mobile/hasil', compact('rekap', 'kelasList', 'startDate', 'endDate', 'kelasFilter'));
    }
}
