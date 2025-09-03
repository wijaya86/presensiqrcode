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
class RekapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   
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

                return view('pages/Rekapitulasi/Rekap', compact('rekap', 'kelasList', 'startDate', 'endDate', 'kelasFilter'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
            $startDate   = $request->get('start_date', Carbon::now()->startOfMonth()->toDateString());
    $endDate     = $request->get('end_date', Carbon::now()->toDateString());
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

    // Nama default untuk file PDF
    $kelasNama = "Semua_Kelas";

    // Kalau pilih kelas tertentu
    if (!empty($kelasFilter) && $kelasFilter !== "0") {
        $query->where('kelasis.id', $kelasFilter);

        // Ambil nama kelas dari tabel
        $kelas = DB::table('kelasis')->where('id', $kelasFilter)->first();
        if ($kelas) {
            $kelasNama = str_replace(' ', '_', $kelas->NamaKelas);
        }
    }

    // Ambil data absensi sesuai filter
    $rekap = $query->get();

    // Buat PDF
    $pdf = Pdf::loadView('pages.Rekapitulasi.Rekappdf', [
        'rekap'       => $rekap,
        'startDate'   => $startDate,
        'endDate'     => $endDate,
        'kelasFilter' => $kelasFilter,
    ])->setPaper('a4', 'portrait');

    // Download PDF dengan nama sesuai filter
    return $pdf->download("rekap_kehadiran_{$kelasNama}_{$startDate}_{$endDate}.pdf");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
