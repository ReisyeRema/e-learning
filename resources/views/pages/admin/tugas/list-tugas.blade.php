@extends('layouts.app')

@section('title', 'Daftar List Tugas')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-3">
                                Daftar List Tugas {{ $pembelajaran->nama_mapel }} - {{ $kelasData->nama_kelas }}
                            </h4>

                            @php
                                $tugasTerpilih = $tugasList->firstWhere('id', request('tugas_id'));
                            @endphp

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            @endif


                            @if ($tugasTerpilih)
                                <div class="mb-4" id="tugas-{{ $tugasTerpilih->id }}">
                                    <h5><strong>{{ $tugasTerpilih->judul }}</strong></h5>

                                    <div class="table-responsive">
                                        <table id="myTable" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 5%;">No</th>
                                                    <th>Nama Siswa</th>
                                                    <th>File Submit</th>
                                                    <th>LINK</th>
                                                    <th>Status</th>
                                                    <th style="text-align: center;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($enrollments as $i => $enroll)
                                                    @php
                                                        $key = $tugasTerpilih->id . '-' . $enroll->siswa->id;
                                                        $submission = $submitTugas->has($key)
                                                            ? $submitTugas[$key]->first()
                                                            : null;
                                                    @endphp
                                                    <tr>
                                                        <td style="text-align: center;">{{ $i + 1 }}</td>
                                                        <td>{{ $enroll->siswa->name }}</td>
                                                        <td>
                                                            @if ($submission && $submission->file_path)
                                                                <a href="https://drive.google.com/file/d/{{ $submission->file_path }}/view"
                                                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    Lihat File
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @if ($submission && $submission->url)
                                                                <a href="{{ $submission->url }}" target="_blank"
                                                                    class="btn btn-outline-info btn-sm">
                                                                    Kunjungi Link
                                                                </a>
                                                            @else
                                                                <span class="text-muted">-</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if ($submission)
                                                                @php
                                                                    $submittedAt = strtotime($submission->created_at);
                                                                    $deadline = strtotime($tugasTerpilih->pertemuanTugas->where('tugas_id', $tugasTerpilih->id)->first()?->deadline);
                                                                @endphp
                                                        
                                                                @if ($submittedAt <= $deadline)
                                                                    <span class="badge badge-success">Sudah Mengumpulkan</span>
                                                                @else
                                                                    <span class="badge badge-warning">Terlambat</span>
                                                                @endif
                                                            @else
                                                                <span class="badge badge-danger">Belum Mengumpulkan</span>
                                                            @endif
                                                        </td>
                                                        
                                                        <td style="text-align: center;">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                @if ($submission)
                                                                    <form
                                                                        action="{{ route('submit-tugas.updateSkor', $submission->id) }}"
                                                                        method="POST" class="d-flex align-items-center">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <input type="text" name="skor"
                                                                            value="{{ $submission->skor }}"
                                                                            class="form-control form-control-sm mr-2"
                                                                            style="width: 60px;" min="0"
                                                                            max="100">
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-success">Simpan</button>
                                                                    </form>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    Tugas tidak ditemukan atau belum dipilih.
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
