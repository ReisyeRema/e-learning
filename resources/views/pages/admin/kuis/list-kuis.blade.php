@extends('layouts.app')

@section('title', 'Daftar List Kuis')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-3">
                                Daftar List Kuis {{ $pembelajaran->nama_mapel }} - {{ $kelasData->nama_kelas }}
                            </h4>

                            @php
                                $kuisTerpilih = $kuisList->firstWhere('id', request('kuis_id'));
                            @endphp

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            @if ($kuisTerpilih)
                                <div class="mb-4" id="tugas-{{ $kuisTerpilih->id }}">
                                    <h5><strong>{{ $kuisTerpilih->judul }}</strong></h5>

                                    <div class="table-responsive">
                                        <table id="myTable" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 5%;">No</th>
                                                    <th style="text-align: center;">Nama Siswa</th>
                                                    <th style="text-align: center;">Status</th>
                                                    <th style="text-align: center;">Nilai</th>
                                                    <th style="text-align: center;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($enrollments as $i => $enroll)
                                                    @php
                                                        $key = $kuisTerpilih->id . '-' . $enroll->siswa->id;
                                                        $submission = $hasilKuis->has($key)
                                                            ? $hasilKuis[$key]->first()
                                                            : null;
                                                    @endphp
                                                    <tr style="text-align: center">
                                                        <td style="text-align: center;">{{ $i + 1 }}</td>
                                                        <td>{{ $enroll->siswa->name }}</td>

                                                        <td>
                                                            @if ($submission)
                                                                @php
                                                                    $submittedAt = strtotime($submission->created_at);
                                                                    $deadline = strtotime(
                                                                        $kuisTerpilih->pertemuanKuis
                                                                            ->where('kuis_id', $kuisTerpilih->id)
                                                                            ->first()?->deadline,
                                                                    );
                                                                @endphp

                                                                @if ($submittedAt <= $deadline)
                                                                    <span class="badge badge-success">Sudah
                                                                        Mengerjakan</span>
                                                                @else
                                                                    <span class="badge badge-warning">Terlambat</span>
                                                                @endif
                                                            @else
                                                                <span class="badge badge-danger">Belum Mengerjakan</span>
                                                            @endif
                                                        </td>
                                                        <td style="text-align: center;">
                                                            @if ($submission && $submission->skor_total)
                                                                <strong>{{ $submission->skor_total }}</strong>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="{{ route('hasil-kuis.show', [
                                                                'mapel' => Str::slug($pembelajaran->nama_mapel, '-'),
                                                                'kelas' => Str::slug($kelasData->nama_kelas, '-'),
                                                                'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                                                                'semester' => Str::slug($pembelajaran->semester, '-'),
                                                                'kuis' => $kuisTerpilih->id,
                                                                'siswa' => $enroll->siswa->id,
                                                            ]) }}"
                                                                class="btn btn-sm btn-outline-primary btn-fw">
                                                                Lihat Jawaban
                                                            </a>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Kuis tidak ditemukan atau belum dipilih.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
