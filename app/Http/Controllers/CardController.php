<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\Kelasi;
use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class CardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
           $kelasis = Kelasi::whereNotIn('id', [10, 11]) // tidak tampilkan id 10 & 11
            ->orderBy('NamaKelas', 'asc')
            ->get(); // <-- WAJIB ditambahkan

        $query = Siswa::with('kelasi');

        if ($request->filled('NamaKelas')) {
            $query->where('id_Kelas', $request->NamaKelas);
        }

        $siswas = $query->get();
        return view('pages/Data Siswa/card',compact('siswas','kelasis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       $query = Siswa::with('kelasi');

            if ($request->filled('NamaKelas')) {
                $query->where('id_Kelas', $request->NamaKelas);
            }

            $siswas = $query->get();

            $pdf = \PDF::loadView('pages.Data Siswa.Cardpdf', compact('siswas'))
                ->setPaper('a4', 'portrait');

            $namaFile = $request->filled('NamaKelas')
                ? 'Kartu_Absensi_Kelas_'.$request->NamaKelas.'.pdf'
                : 'Kartu_Absensi_Semua.pdf';

            return $pdf->download($namaFile);
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
    public function show(Siswa $siswa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Siswa $siswa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        //
    }
}
