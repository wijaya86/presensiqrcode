<?php

namespace App\Http\Controllers;

use App\Models\Kelasi;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Siswa;
use Illuminate\Http\Request;
//import return type View
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
//import return type redirectResponse
use Illuminate\Http\RedirectResponse;
class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         $siswa =Siswa::with('kelasi')->get();
        return view('pages/Data Siswa/DaftarSiswa',compact('siswa'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $kelas = Kelasi::whereNotIn('id', [10, 11])
            ->get();
        return view('pages/Data Siswa/DataSiswa',compact('kelas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
                $request->validate([
                'NISN' => 'required',
                'NamaSiswa' => 'required|unique:siswas,NISN',
                'id_Kelas' => 'required',
                'Jenkel' => 'required',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);

            $data = $request->only('NISN', 'NamaSiswa', 'id_Kelas','Jenkel');
           $payload = $data['NISN'] . ' - ' . $data['NamaSiswa'];
            // Generate QR Code sebagai base64
            $qr = base64_encode(QrCode::format('png')->size(200)->generate($payload));
            
            // Simpan data
            $siswa = new Siswa($data);
            $siswa->qrcode = $qr;
            
            // // Proses upload foto
            // if ($request->hasFile('foto')) {
            //     $file = $request->file('foto');
            //     $namaFile = time() . '.' . $file->getClientOriginalExtension();
            //     $file->move(public_path('foto'), $namaFile);
            //     $siswa->foto = $namaFile;
            // }

            $siswa->save();

            return redirect()->route('siswa.index')->with('success', 'Siswa berhasil ditambahkan');
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
       
       $kelas = Kelasi::whereNotIn('id', [10, 11])
            ->get(); // untuk dropdown
        return view('pages/Data Siswa/EditSiswa',compact('siswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Siswa $siswa)
    {
        $request->validate([
        'NISN' => 'required',
        'NamaSiswa' => 'required',
        'Jenkel' => 'required|in:L,P',
        'id_Kelas' => 'required|exists:Kelasis,id',
        ]);

        // generate payload untuk QR
        $payload = $request->NISN . ' - ' . $request->NamaSiswa;
        $qr = base64_encode(QrCode::format('png')->size(200)->generate($payload));

        // update data siswa + qr code
        $siswa->update([
            'NISN' => $request->NISN,
            'NamaSiswa' => $request->NamaSiswa,
            'Jenkel' => $request->Jenkel,
            'id_Kelas' => $request->id_Kelas,
            'qrcode' => $qr,
        ]);
            return redirect()->route('siswa.index')->with('success', 'Data kelas berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Siswa $siswa)
    {
        $siswa->delete();
        return redirect()->route('siswa.index')->with('success', 'Data kelas berhasil dihapus.');
    }
}
