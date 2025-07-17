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
                                <p class="card-title">Daftar Mata Pelajaran - {{ $waliKelas->kelas->nama_kelas }}
                                    ({{ $waliKelas->tahunAjaran->nama_tahun }})</p>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-3">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th style="text-align: center">Nama</th>
                                            <th style="text-align: center">Guru</th>
                                            <th style="text-align: center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($mapelList as $item)
                                            <tr style="text-align: center">
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td>{{ $item->nama_mapel }}</td>
                                                <td>{{ $item->guru->name ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button" class="btn btn-sm btn-outline-success btn-fw"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal{{ $item->id }}">
                                                            Lihat
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
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
