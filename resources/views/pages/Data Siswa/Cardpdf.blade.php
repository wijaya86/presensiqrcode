<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 15px;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 0;
            padding: 0;
            text-align: left;
        }
        .card {
            display: inline-block;
            vertical-align: top;
            width: 5.8cm; /* <= lebih kecil supaya muat 3 */
            height: 8.3cm;
            border: 2px solid #0d6efd;
            border-radius: 6px;
            padding: 5px;
            margin: 0.2cm; /* margin juga dikecilkan */
            text-align: center;
            box-sizing: border-box;
        }
        .foto {
            width: 2.3cm;
            height: 3.0cm;
            object-fit: cover;
            border: 2px solid #0d6efd;
            border-radius: 6px;
            background: #fff;
        }
        .nama {
            margin-top: 5px;
            font-weight: bold;
            font-size: 12px;
            color: #0d6efd;
        }
        .info {
            font-size: 10px;
            margin: 2px 0;
        }
        .qr {
            width: 2.7cm;
            height: 2.7cm;
            margin-top: 6px;
        }
        .footer {
            margin-top: 3px;
            font-size: 9px;
            border-top: 1px solid #ccc;
            padding-top: 2px;
        }
    </style>
</head>
<body>
    @foreach($siswas as $siswa)
        <div class="card">
            <img src="{{ public_path('foto/'.$siswa->foto) }}" class="foto">
            <div class="nama">{{ $siswa->NamaSiswa }}</div>
            <div class="info">NIS: {{ $siswa->NISN }}</div>
            <div class="info">Kelas: {{ $siswa->kelasi->NamaKelas }}</div>
            @if($siswa->qrcode)
                <img src="data:image/png;base64,{{ $siswa->qrcode }}" class="qr">
            @endif
            <div class="footer">
                SMKN 1 Karangtengah<br>
                PPLG
            </div>
        </div>
    @endforeach
</body>
</html>
