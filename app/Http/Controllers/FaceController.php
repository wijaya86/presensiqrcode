<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class FaceController extends Controller
{
    public function index() {
        return view('pages/Data Face/face');
    }
    public function camera()
    {
        // halaman auto-scan kamera
        return view('pages/Data Face/face_camera');
    }
    public function detect(Request $request) {
      $image = $request->input('photo');
        if (!$image) {
            return response()->json(['error'=>'Foto tidak valid'], 400);
        }

        try {
            $pythonUrl = 'http://127.0.0.1:5000/detect';
            $response = Http::post($pythonUrl, ['image' => $image]);
            $result = $response->json();

            return response()->json($result); // ← return JSON langsung
        } catch (\Exception $e) {
            return response()->json(['error' => 'Python API tidak tersedia: '.$e->getMessage()], 500);
        }
        
    }

}       