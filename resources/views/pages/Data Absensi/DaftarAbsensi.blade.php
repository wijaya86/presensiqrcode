@extends('layout.master')
@section('content')
        <!-- DataTales Example -->
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
                                            <th>Tool</th>
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
                                            <th>Tool</th>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        @foreach($rekap as $rekap)
                                        <tr>
                                             <td>{{$rekap->tanggal}}</td>
                                            <td>{{$rekap->NISN}}</td>
                                            <td>{{$rekap->NamaSiswa}}</td>
                                            <td>{{$rekap->Jenkel}}</td>
                                            <td>{{$rekap->NamaKelas}}</td>
                                            <td>{{$rekap->kehadiran}}</td>
                                          <td>
                                               
                                               <a href="{{route('absensi.edit', $rekap->id) }}" class="btn btn-warning btn-circle btn-sm" data-toggle="tooltip" title="Edit">
                                        <i class="fas fa-info-circle"></i>
                                    </a>
                                    <form action="{{ route('absensi.destroy', $rekap->id) }}" method="POST">
                                     @csrf @method('DELETE')
                                    <button class="btn btn-danger btn-circle btn-sm" data-toggle="tooltip" title="Delete" onclick="return confirm('Yakin?')">
                                        <i class="fas fa-trash"></i></button>
                                    </form>
                                </td>                                                                                             
                                        </tr>  
                                         @endforeach                                                                                                               
                                    </tbody>
                                   
                                </table>
                            </div>
                      
 
@endsection

