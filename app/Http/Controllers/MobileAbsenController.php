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

class MobileAbsenController extends Controller
{
    public function index()
    {
        
        return view('pages/Mobile/dashboard');
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

         return redirect()->route('mobileScan.create')
            ->with('message','Absensi berhasil!');
       

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
}
