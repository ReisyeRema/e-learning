@extends('layouts.app')

@section('title', 'Data Tahun Ajaran')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Tahun Ajaran</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addTahunModal">
                                    Tambah Data
                                </button>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama Kelas</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tahun_ajaran as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $item->nama_tahun }} </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button" class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal" data-target="#editTahunModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete({{ $item->id }})">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('tahun-ajaran.destroy', $item->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editTahunModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="editTahunModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editTahunModalLabel{{ $item->id }}">
                                                                Edit Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('tahun-ajaran.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="nama_tahun">Nama Tahun</label>
                                                                    <input type="text" class="form-control @error('nama_tahun') is-invalid @enderror"
                                                                        id="nama_tahun" name="nama_tahun" value="{{ old('nama_tahun', $item->nama_tahun) }}">
                                                                    @error('nama_tahun')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

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

    <!-- Modal for adding new kelas -->
    <div class="modal fade" id="addTahunModal" tabindex="-1" aria-labelledby="addTahunModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTahunModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_tahun">Nama Tahun</label>
                            <input type="text" class="form-control @error('nama_tahun') is-invalid @enderror"
                                id="nama_tahun" name="nama_tahun" value="{{ old('nama_tahun') }}">
                            @error('nama_tahun')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
