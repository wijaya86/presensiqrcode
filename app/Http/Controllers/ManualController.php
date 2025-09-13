<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Siswa;
use App\Models\Kelasi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
//import return type View
use Illuminate\View\View;

//import return type redirectResponse
use Illuminate\Http\RedirectResponse;

class ManualController extends Controller
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
                        'absensis.id',
                        'absensis.tanggal',
                        'siswas.NISN',
                        'siswas.NamaSiswa',
                        'siswas.Jenkel',
                        'kelasis.NamaKelas',
                        'kehadirans.kehadiran'
                    )
                    
                    ->whereBetween('absensis.tanggal', [$startDate, $endDate])
                    ->orderBy('absensis.tanggal', 'desc')
                    ->orderBy('siswas.NamaSiswa', 'asc');

                // Kalau pilih kelas tertentu (bukan semua)
                if (!empty($kelasFilter) && $kelasFilter !== "0") {
                    $query->where('kelasis.id', $kelasFilter);
                }

                $rekap = $query->get();

                // ambil daftar kelas untuk dropdown
                $kelasList = DB::table('kelasis')
                    ->whereNotIn('id', [10, 11])
                    ->get();

                return view('pages/Data Absensi/DaftarAbsensi', compact('rekap', 'kelasList', 'startDate', 'endDate', 'kelasFilter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
         
        $request->validate([
           'NISN'         => 'required',
           'tanggal'         => 'required',
           'id_Kehadiran'     => 'required'
        ]);

        // Cek duplikat
    $cek = \App\Models\Absensi::where('NISN', $request->NISN)
                ->where('tanggal', $request->tanggal)
                ->first();

    if ($cek) {
        return redirect()->back()->with('message', 'Siswa ini sudah diabsen di tanggal tersebut!');
    }
         Absensi::create([
           'NISN'         => $request->NISN,
           'tanggal'         => $request->tanggal,
            'id_Kehadiran'         => $request->id_Kehadiran
        ]);

         return redirect()->route('absensi.create')
            ->with('message',' Absensi berhasil!');
       

    }

    /**
     * Display the specified resource.
     */
    public function autocomplete(Request $request)
    {
        $term = $request->get('term');
        $siswas = Siswa::with('kelasi')
            ->where('NamaSiswa', 'LIKE', "%{$term}%")
            ->get();

        $data = [];
        foreach ($siswas as $siswa) {
            $data[] = [
                'label' => $siswa->NamaSiswa . ' - ' . $siswa->NISN . ' (' . $siswa->kelasi->NamaKelas . ')',
                'value' => $siswa->NISN,
                'kelas' => $siswa->kelasi->NamaKelas,
                'nama'  => $siswa->NamaSiswa,
            ];
        }

        return response()->json($data);
    }

    public function show(Request $request)
    {
       
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
