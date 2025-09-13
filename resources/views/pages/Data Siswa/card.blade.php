@extends('layout.master')
@section('content')
<style>
.kartu-siswa {
    width: 8.56cm;
    height: 5.4cm;
    border: 2px solid #0d6efd;
    border-radius: 6px;
    background: #fff;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    padding: 5px;
}
.foto-wrapper { display: flex; justify-content: center; margin-top: 10px; }
.foto-kartu {
    width: 2.5cm; height: 3.2cm;
    object-fit: cover;
    border: 2px solid #0d6efd;
    border-radius: 6px;
    background: #fff;
    box-shadow: 0 0 6px rgba(13, 110, 253, 0.4);
}
.qr-wrapper { display: flex; justify-content: center; margin-top: 5px; }
.qr-kartu { width: 100px; height: 100px; }
.footer-kartu { text-align: center; font-size: 14px; margin-top: auto; }
</style>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Kartu Absensi</h6>
            
            <!-- Tombol Download Ikut Filter -->
            <a href="{{ route('card.create', ['NamaKelas' => request('NamaKelas')]) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-file-pdf"></i> 
                Download {{ request('NamaKelas') ? 'Kartu Kelas ' . $kelasis->where('id', request('NamaKelas'))->first()->NamaKelas : 'Semua Kartu' }}
            </a>
        </div>
    </div>

    <div class="card-body">
        <!-- FILTER PER KELAS -->
        <form method="GET" action="{{ route('card.index') }}" class="mb-4">
            <div class="row">
                <div class="col-md-4">
                    <label for="kelas" class="form-label fw-bold">Filter Kelas</label>
                    <select name="NamaKelas" id="NamaKelas" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="">-- Semua Kelas --</option>
                       @foreach($kelasis as $kelas)
                        <option value="{{ $kelas->id }}" {{ request('NamaKelas') == $kelas->id ? 'selected' : '' }}>
                            {{ $kelas->NamaKelas }}
                        </option>
                    @endforeach
                    </select>
                </div>
            </div>
        </form>

        <!-- Kartu Siswa -->
        <div class="row">
            @forelse($siswas as $siswa)
                <div class="col-lg-4 col-md-4 col-sm-6 mb-4">
                    <div class="card shadow h-100 text-center p-2 kartu-siswa">
                        <div class="foto-wrapper">
                            <img src="{{ asset('foto/'.$siswa->foto) }}" class="foto-kartu" alt="Foto Siswa">
                        </div>
                        <div class="mt-2">
                            <h5 class="card-title mb-1">{{ $siswa->NamaSiswa }}</h5>
                            <p class="text-muted small mb-1">NIS: {{ $siswa->NISN }}</p>
                            <p class="text-muted small mb-1">Kelas: {{ $siswa->kelasi->NamaKelas }}</p>
                        </div>
                        @if($siswa->qrcode)
                            <div class="qr-wrapper">
                                <img src="data:image/png;base64,{{ $siswa->qrcode }}" class="qr-kartu" alt="QR Code">
                            </div>
                        @endif
                        <div class="footer-kartu">
                            <small>SMKN 1 Karangtengah</small><br>
                            <small>PPLG</small>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">Tidak ada data siswa untuk filter ini.</p>
                </div>
            @endforelse
        </div>
    </div>

@endsection
