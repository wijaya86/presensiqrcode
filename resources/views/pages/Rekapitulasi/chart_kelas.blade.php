@extends('layout.master')

@section('content')
<div class="card shadow mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-primary">Grafik Kehadiran Keseluruhan Kelas</h6>
        <form method="GET" action="{{ route('chart.index') }}" class="form-inline">
                                    <select name="bulan" class="form-control mr-2">
                                    @for($i = 1; $i <= 12; $i++)
                                        <option value="{{ $i }}" 
                                            {{ request('bulan', now()->month) == $i ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::createFromDate(null, $i, 1)->translatedFormat('F') }}
                                        </option>
                                    @endfor
                                    </select>

                                    <select name="tahun" class="form-control mr-2">
                                        @for($t = now()->year - 5; $t <= now()->year + 1; $t++)
                                        <option value="{{ $t }}" 
                                            {{ request('tahun', now()->year) == $t ? 'selected' : '' }}>
                                            {{ $t }}
                                        </option>
                                    @endfor
                                    </select>

                                    <select name="kelas" class="form-control mr-2">
                                        <option value="all">Semua Kelas</option>
                                    @foreach($kelasList as $kelas)
                                    <option value="{{ $kelas->id }}" {{ $kelasId == $kelas->id ? 'selected' : '' }}>
                                        {{ $kelas->NamaKelas }}
                                    </option>
                                @endforeach
                                    </select>

                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fas fa-filter"></i> Filter
                                    </button>
                                </form>
                            </div>


    <div class="card-body text-center">
    <!-- Chart -->
    <div style="width: 250px; height: 250px; margin: auto;">
        <canvas id="chartKehadiran"></canvas>
    </div>

    <!-- Legend manual di bawah -->
    <div id="chartLegend" class="mt-3"></div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script>
    const ctx = document.getElementById('chartKehadiran').getContext('2d');

    const colors = {
        "Hadir": 'rgba(54, 163, 235, 0.8)',
        "Ijin": 'rgba(229, 231, 71, 0.92)',
        "Sakit": 'rgba(6, 228, 91, 0.44)',
        "Alpha": 'rgba(245, 4, 4, 0.77)'
    };

    const labels = {!! json_encode($data->keys()) !!};
    const values = {!! json_encode($data->values()) !!};

    const chart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: values,
                backgroundColor: labels.map(l => colors[l] || 'rgba(128, 128, 128, 0.7)'),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }, // legend bawaan off
                title: { 
                    display: true, 
                    text: 'Rekap Presensi ' +
                          '{{ \Carbon\Carbon::createFromDate(null, (int)$bulan, 1)->translatedFormat("F") }} {{ $tahun }} ' +
                          '{{ $kelasId == "all" ? "(Semua Kelas)" : "(Kelas " . $kelasList->where("id",$kelasId)->first()->NamaKelas . ")" }}'
                },
                datalabels: {
                    formatter: (value, ctx) => {
                        let sum = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                        let percentage = (value * 100 / sum).toFixed(1) + "%";
                        return value + " (" + percentage + ")";
                    },
                    color: "#fff",
                    font: { weight: "bold" }
                }
            }
        },
        plugins: [ChartDataLabels]
    });

    // Legend manual di bawah chart
    function generateLegend(chart) {
        const total = chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
        let legendHTML = '<ul style="list-style:none; padding-left:0; display:flex; justify-content:center; flex-wrap:wrap;">';
        chart.data.labels.forEach((label, i) => {
            const value = chart.data.datasets[0].data[i];
            const percentage = ((value / total) * 100).toFixed(1);
            const color = chart.data.datasets[0].backgroundColor[i];
            legendHTML += `
                <li style="margin:8px 15px; display:flex; align-items:center;">
                    <span style="width:15px; height:15px; display:inline-block; background-color:${color}; margin-right:8px; border-radius:3px;"></span>
                    <span>${label}: <b>${value}</b> (${percentage}%)</span>
                </li>
            `;
        });
        legendHTML += '</ul>';
        return legendHTML;
    }

    document.getElementById('chartLegend').innerHTML = generateLegend(chart);
</script>
@endsection
