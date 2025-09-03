@extends('layout.master')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Daftar Absensi</h6>
        <form method="GET" action="{{ route('rekap.index') }}" class="form-inline">
            <label class="mr-2">Dari Tanggal</label>
            <input type="date" name="start_date" class="form-control mr-2" value="{{ request('start_date')?? \Carbon\Carbon::now()->toDateString() }}">

            <label class="mr-2">Sampai Tanggal</label>
            <input type="date" name="end_date" class="form-control mr-2" value="{{ request('end_date')?? \Carbon\Carbon::now()->toDateString() }}">

            <select name="kelas" class="form-control mr-2">
                <option value="0" {{ $kelasFilter == "0" ? 'selected' : '' }}>Semua Kelas</option>
                @foreach($kelasList as $k)
                    <option value="{{ $k->id }}" {{ $kelasFilter == $k->id ? 'selected' : '' }}>
                        {{ $k->NamaKelas }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="btn btn-primary btn-sm">
                <i class="fas fa-filter"></i> Filter
            </button>
        </form>
    </div>

    <div class="card-body">
        <div class="mb-3 text-right">
            {{-- Tombol Download PDF --}}
            <a href="{{ route('rekap.create', [
                'start_date' => request('start_date'),
                'end_date'   => request('end_date'),
                'kelas'      => request('kelas')
            ]) }}" class="btn btn-danger btn-sm">
                <i class="fas fa-file-pdf"></i> Download PDF
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>L/P</th>
                        <th>Kelas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Tanggal</th>
                        <th>NIS</th>
                        <th>Nama Siswa</th>
                        <th>L/P</th>
                        <th>Kelas</th>
                        <th>Keterangan</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($rekap as $r)
                    <tr>
                        <td>{{ $r->tanggal }}</td>
                        <td>{{ $r->NISN }}</td>
                        <td>{{ $r->NamaSiswa }}</td>
                        <td>{{ $r->Jenkel }}</td>
                        <td>{{ $r->NamaKelas }}</td>
                        <td>{{ $r->kehadiran }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    
</div>
@endsection