@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')
    <div class="row">
        @include('components.nav-walas')

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">

                            <!-- Title Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title">Daftar Siswa - {{ $waliKelas->kelas->nama_kelas }}
                                    ({{ $waliKelas->tahunAjaran->nama_tahun }})</p>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-3">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th style="text-align: center">Nama</th>
                                            <th style="text-align: center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($siswaList as $item)
                                            <tr style="text-align: center">
                                                <td> {{ $loop->iteration }} </td>
                                                <td> {{ $item->user->name }} </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                            data-toggle="modal"
                                                            data-target="#DetailModal{{ $item->id }}">
                                                            Lihat
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Detail Siswa -->
                                            <div class="modal fade" id="DetailModal{{ $item->id }}" tabindex="-1"
                                                role="dialog" aria-labelledby="DetailModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-3">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"
                                                                id="DetailModalLabel{{ $item->id }}">
                                                                Detail Siswa
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Nama Lengkap:</strong><br>
                                                                    {{ $item->user->name }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>NIS:</strong><br>
                                                                    {{ $item->nis }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Tempat Lahir:</strong><br>
                                                                    {{ $item->tempat_lahir }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Tanggal Lahir:</strong><br>
                                                                    {{ $item->tanggal_lahir->format('d-m-Y') }}
                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Jenis Kelamin:</strong><br>
                                                                    {{ ucfirst($item->jenis_kelamin) }}
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Alamat:</strong><br>
                                                                    {{ $item->alamat }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
