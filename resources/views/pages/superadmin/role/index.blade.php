@extends('layouts.app')

@section('title', 'Data Role')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Mata Pelajaram</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addRolesModal">
                                    Tambah Data
                                </button>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama Mata Pelajaran</th>
                                            <th width="30%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($roles as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ $item->name }} </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button" class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal" data-target="#editRolesModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <!-- Button to Add Permission -->
                                                        <a href="{{ route('roles.addPermissionToRole', $item->id) }}" class="btn btn-sm btn-outline-warning btn-fw mr-3">
                                                            Add/Edit Role Permission
                                                        </a>
                                                        <!-- Button delete -->
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                            data-toggle="modal" data-target="#deleteRolesModal{{ $item->id }}">
                                                            Delete
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editRolesModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="editRolesModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="editRolesModalLabel{{ $item->id }}">
                                                                Edit Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('roles.update', $item->id) }}" method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="name">Nama Role</label>
                                                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                                        id="name" name="name" value="{{ old('name', $item->name) }}">
                                                                    @error('name')
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

                                            <!-- Modal for Deleting Kelas -->
                                            <div class="modal fade" id="deleteRolesModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="deleteRolesModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteRolesModalLabel{{ $item->id }}">
                                                                Hapus Role</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <p>Apakah Anda yakin ingin menghapus kelas {{ $item->name }}?</p>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('roles.destroy', $item->id) }}" method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete</button>
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
    <div class="modal fade" id="addRolesModal" tabindex="-1" aria-labelledby="addRolesModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRolesModalLabel">Tambah Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('roles.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Nama Role</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="name" name="name" value="{{ old('name') }}">
                            @error('name')
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
