@extends('layouts.app')

@section('title', 'Data Enroll Siswa')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Enroll Siswa</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addKelasModal">
                                    Tambah Data
                                </button>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama Siswa</th>
                                            <th>Nama Mata Pelajaran</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($enroll_siswa as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ optional($item->siswa)->name }} </td>
                                                <td> {{ optional($item->pembelajaran)->nama_mapel }} -
                                                    {{ optional($item->pembelajaran->guru)->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <!-- Button delete -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                            data-toggle="modal"
                                                            data-target="#deleteKelasModal{{ $item->id }}">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editKelasModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="editKelasModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editKelasModalLabel{{ $item->id }}">
                                                                Edit Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('enroll-siswa.update', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Nama Siswa</label>
                                                                    <select name="siswa_id"
                                                                        class="form-control @error('siswa_id') is-invalid @enderror"
                                                                        id="exampleSelectguru">
                                                                        <option value="">Pilih guru</option>
                                                                        @foreach ($siswa as $s)
                                                                            <option value="{{ $s->id }}"
                                                                                {{ old('siswa_id', $item->siswa_id) == $s->id ? 'selected' : '' }}>
                                                                                {{ $s->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('siswa_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Mata Pelajaran</label>
                                                                    <select name="pembelajaran_id"
                                                                        class="form-control @error('pembelajaran_id') is-invalid @enderror"
                                                                        id="exampleSelectguru">
                                                                        <option value="">Pilih Mata Pelajaran</option>
                                                                        @foreach ($pembelajaran as $p)
                                                                            <option value="{{ $p->id }}"
                                                                                {{ old('pembelajaran_id', $item->pembelajaran_id) == $p->id ? 'selected' : '' }}>
                                                                                {{ $p->nama_mapel }} - {{ optional($p->guru)->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('pembelajaran_id')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light"
                                                                    data-dismiss="modal">Close</button>
                                                                <button type="submit" class="btn btn-primary">Save
                                                                    changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal for Deleting Kelas -->
                                            <div class="modal fade" id="deleteKelasModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="deleteKelasModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="deleteKelasModalLabel{{ $item->id }}">
                                                                Hapus Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus kelas
                                                                {{ $item->nama_kelas }}?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('enroll-siswa.destroy', $item->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
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
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('enroll-siswa.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Nama Siswa</label>
                            <select name="siswa_id" class="form-control @error('siswa_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Siswa</option>
                                @foreach ($siswa as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('siswa_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('siswa_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectKelas">Pembelajaran</label>
                            <select name="pembelajaran_id"
                                class="form-control @error('pembelajaran_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Pembelajaran</option>
                                @foreach ($pembelajaran as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('pembelajaran_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->nama_mapel }} - {{ optional($item->guru)->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('pembelajaran_id')
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
