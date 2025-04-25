@extends('layouts.main')

@section('title', 'Dashboard Siswa')

@section('content')

    {{-- DEADLINE TERDEKAT --}}
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Deadline Terdekat</h4>

        <div class="row">
            {{-- Tugas --}}
            @foreach ($tugasDeadline as $tugas)
                <div class="col-md-6 mb-3">
                    <div class="border-start border-4 border-primary ps-3">
                        <h6 class="text-primary fw-bold mb-1">{{ $tugas->tugas->judul }}</h6>
                        <small class="text-muted d-block mb-1">
                            Mapel: {{ $tugas->pembelajaran->nama_mapel }}
                        </small>
                        <small class="text-danger fw-semibold">
                            Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach

            {{-- Kuis --}}
            @foreach ($kuisDeadline as $kuis)
                <div class="col-md-6 mb-3">
                    <div class="border-start border-4 border-warning ps-3">
                        <h6 class="text-warning fw-bold mb-1">{{ $kuis->kuis->judul }}</h6>
                        <small class="text-muted d-block mb-1">
                            Mapel: {{ $kuis->pembelajaran->nama_mapel }}
                        </small>
                        <small class="text-danger fw-semibold">
                            Deadline: {{ \Carbon\Carbon::parse($kuis->deadline)->translatedFormat('d M Y H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach

            @if ($tugasDeadline->isEmpty() && $kuisDeadline->isEmpty())
                <div class="col-12">
                    <div class="alert alert-secondary">
                        Tidak ada tugas atau kuis yang akan segera deadline.
                    </div>
                </div>
            @endif
        </div>
    </div>


    {{-- STATUS PEMBELAJARAN TERAKHIR --}}
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Status Pembelajaran Terakhir</h4>

        <div class="row">
            @foreach ($statusPembelajaran as $status)
                <div class="col-md-12 mb-3">
                    <div class="card p-3 border-0 shadow-sm bg-white rounded-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="icon-container"
                                style="width: 40px; height: 40px; border-radius: 50%; background-color: {{ $status->status == 'selesai' ? '#28a745' : '#ffc107' }}; display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-check text-white"></i>
                            </div>
                            <div>
                                <h6 class="ms-3 fw-bold mt-3 text-dark">{{ $status->activity }}</h6>
                                <p class=" ms-3 text-muted fs-6">{{ $status->details }}</p>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Terakhir:
                                {{ \Carbon\Carbon::parse($status->performed_at)->translatedFormat('l, d F Y - H:i') }}
                            </small>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>



    {{-- PROGRESS BELAJAR --}}
    <div class="card shadow-sm p-4 mb-4 bg-white rounded-3">
        <h4 class="mb-4 text-success">Progress Belajar</h4>

        {{-- Filter Form --}}
        <form method="GET" id="filterForm" class="mb-4">
            <div class="row align-items-center">
                <div class="col-md-3 col-sm-4">
                    <label for="pembelajaran_key" class="form-label fw-semibold mb-0">Pilih Pembelajaran:</label>
                </div>
                <div class="col-md-9 col-sm-8">
                    <select name="pembelajaran_key" id="pembelajaran_key" class="form-select"
                        onchange="document.getElementById('filterForm').submit();">
                        <option value="">-- Semua Pembelajaran --</option>
                        @foreach ($pembelajaranList as $p)
                            @php
                                $key = $p->nama_mapel . '|' . $p->kelas->id . '|' . $p->tahunAjaran->id;
                            @endphp
                            <option value="{{ $key }}"
                                {{ request('pembelajaran_key') == $key ? 'selected' : '' }}>
                                {{ $p->nama_mapel }} - {{ $p->kelas->nama_kelas }} - {{ $p->tahunAjaran->nama_tahun }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </form>


        {{-- Progress --}}
        @if ($progressList->isNotEmpty())
            <div class="row">
                @foreach ($progressList as $i => $progress)
                    @php
                        $onlyOneChart = $progress['tugas_total'] == 0 || $progress['kuis_total'] == 0;
                        $canvasSize = $onlyOneChart ? 220 : 150;
                    @endphp

                    <div class="col-md-12 mb-4">
                        <h6 class="text-primary text-center mb-4">{{ $progress['pembelajaran']->nama }}</h6>
                        <div class="row justify-content-center gap-2">
                            @if ($progress['tugas_total'] > 0)
                                <div
                                    class="{{ $onlyOneChart ? 'col-md-6' : 'col-md-5' }} d-flex flex-column align-items-center justify-content-center">
                                    <canvas id="tugasChart-{{ $i }}" width="{{ $canvasSize }}"
                                        height="{{ $canvasSize }}"></canvas>
                                    <p class="mt-3 small text-muted text-center">
                                        Tugas: {{ $progress['tugas_selesai'] }}/{{ $progress['tugas_total'] }}
                                    </p>
                                </div>
                            @endif

                            @if ($progress['kuis_total'] > 0)
                                <div
                                    class="{{ $onlyOneChart ? 'col-md-6' : 'col-md-5' }} d-flex flex-column align-items-center justify-content-center">
                                    <canvas id="kuisChart-{{ $i }}" width="{{ $canvasSize }}"
                                        height="{{ $canvasSize }}"></canvas>
                                    <p class="mt-3 small text-muted text-center">
                                        Kuis: {{ $progress['kuis_selesai'] }}/{{ $progress['kuis_total'] }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="alert alert-warning mt-3">
                Tidak ada data pembelajaran untuk mapel ini.
            </div>
        @endif
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        @foreach ($progressList as $i => $progress)
            // Tugas Chart
            new Chart(document.getElementById('tugasChart-{{ $i }}'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum'],
                    datasets: [{
                        data: [
                            {{ $progress['tugas_selesai'] }},
                            {{ $progress['tugas_total'] - $progress['tugas_selesai'] }}
                        ],
                        backgroundColor: ['#0d6efd', '#e9ecef'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });

            // Kuis Chart
            new Chart(document.getElementById('kuisChart-{{ $i }}'), {
                type: 'doughnut',
                data: {
                    labels: ['Selesai', 'Belum'],
                    datasets: [{
                        data: [
                            {{ $progress['kuis_selesai'] }},
                            {{ $progress['kuis_total'] - $progress['kuis_selesai'] }}
                        ],
                        backgroundColor: ['#ffc107', '#e9ecef'],
                        borderWidth: 1
                    }]
                },
                options: {
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.label + ': ' + context.raw;
                                }
                            }
                        }
                    }
                }
            });
        @endforeach
    </script>
@endpush
