    @extends('layout.master')

    @section('content')
       <div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Daftar log</h6>
            <form method="GET" action="{{ route('logs.index') }}" class="form-inline">
                <label class="mr-2">Dari</label>
                <input type="date" name="start_date" class="form-control mr-2"
                    value="{{ $startDate }}">

                <label class="mr-2">Sampai</label>
                <input type="date" name="end_date" class="form-control mr-2"
                    value="{{ $endDate }}">

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
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tanggal</th>

                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Pesan</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Pesan</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>{{ $log->tanggal }}</td>
                                <td>{{ $log->siswa->NamaSiswa}}</td>
                                <td>{{ $log->siswa->kelasi->NamaKelas}}</td>
                                <td><span class="badge badge-danger">{{ $log->status }}</span></td>
                                <td>{{ $log->pesan }}</td>
                            </tr>
                        @empty
                        
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        

    @endsection