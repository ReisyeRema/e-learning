@extends('layouts.app')

@section('title', 'Export Rekapitulasi')

@section('content')
    <div class="row">
        @include('components.nav-walas')

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body" style="background-color: #e6eefb;">

                    <!-- Title Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="card-title">Export Rekapitulasi Siswa - {{ $waliKelas->kelas->nama_kelas }}
                            ({{ $waliKelas->tahunAjaran->nama_tahun }})</p>
                    </div>

                    <!-- Filter Semester -->
                    <form method="GET" action="">
                        <div class="form-group row">
                            <label for="exampleInputUsername2" class="col-sm-2 col-form-label">Pilih Semester</label>
                            <div class="col-sm-10">
                                <select name="semester" id="semester" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="Ganjil" {{ request('semester') == 'Ganjil' ? 'selected' : '' }}>Ganjil
                                    </option>
                                    <option value="Genap" {{ request('semester') == 'Genap' ? 'selected' : '' }}>Genap
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="list-group">
                        @if (request('semester'))
                            <a href="{{ route('export-tugas-kelas.export', [
                                'kelas' => $slugKelas,
                                'tahunAjaran' => $slugTahunAjaran,
                                'semester' => request('semester'),
                            ]) }}"
                                class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x me-4"></i>
                                <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi Nilai
                                    Tugas Siswa - {{ $waliKelas->kelas->nama_kelas }} Tahun Pelajaran
                                    ({{ $waliKelas->tahunAjaran->nama_tahun }})
                                    @if ($semester)
                                        - Semester {{ $semester }}
                                    @endif
                                </strong>
                            </a>
                        @endif

                        @if (request('semester'))
                            <a href="{{ route('export-kuis-kelas.export', [
                                'kelas' => $slugKelas,
                                'tahunAjaran' => $slugTahunAjaran,
                                'semester' => request('semester'),
                            ]) }}"
                                class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x me-4"></i>
                                <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi Nilai
                                    Kuis
                                    Siswa - {{ $waliKelas->kelas->nama_kelas }} Tahun Pelajaran
                                    ({{ $waliKelas->tahunAjaran->nama_tahun }})
                                    @if ($semester)
                                        - Semester {{ $semester }}
                                    @endif
                                </strong>
                            </a>
                        @endif

                        @if (request('semester'))
                            <a href="{{ route('export-absensi-kelas.export', [
                                'kelas' => $slugKelas,
                                'tahunAjaran' => $slugTahunAjaran,
                                'semester' => request('semester'),
                            ]) }}"
                                class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                                <i class="fas fa-file-alt fa-2x me-4"></i>
                                <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi
                                    Kehadiran
                                    Siswa - {{ $waliKelas->kelas->nama_kelas }} Tahun Pelajaran
                                    ({{ $waliKelas->tahunAjaran->nama_tahun }})
                                    @if ($semester)
                                        - Semester {{ $semester }}
                                    @endif
                                </strong>
                            </a>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
