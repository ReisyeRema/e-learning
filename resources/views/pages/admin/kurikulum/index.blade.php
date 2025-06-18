@extends('layouts.app')

@section('title', 'Data Kurikulum')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Kurikulum</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addKurikulumModal">
                                    Tambah Data
                                </button>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Icon</th>
                                            <th>Nama kurikulum</th>
                                            <th>Deskripsi</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kurikulum as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td>
                                                    @if ($item->icon) <!-- Check if guru data exists -->
                                                        <img src="{{ asset('storage/icon_kurikulum/' . $item->icon) }}" alt="icon" width="50">
                                                    @else
                                                        No Image
                                                    @endif
                                                </td>
                                                <td> {{ $item->nama_kurikulum }} </td>
                                                <td> 
                                                    {{ \Illuminate\Support\Str::limit($item->deskripsi, 80, '...') }} 
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        {{-- tambah komponen --}}
                                                        {{-- <a href="{{ route('komponen-kurikulum.index', ['kurikulum_id' => $item->id]) }}" class="btn btn-sm btn-outline-info btn-fw mr-1">Tambah Komponen</a> --}}

                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-1"
                                                            data-toggle="modal"
                                                            data-target="#editKurikulumModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete({{ $item->id }})">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('kurikulum.destroy', $item->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing kurikulum -->
                                            <div class="modal fade" id="editKurikulumModal{{ $item->id }}"
                                                tabindex="-1" aria-labelledby="editKurikulumModalLabel{{ $item->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editKurikulumModalLabel{{ $item->id }}">
                                                                Edit Kurikulum</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('kurikulum.update', $item->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="nama_kurikulum">Nama Kurikulum <span class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control @error('nama_kurikulum') is-invalid @enderror"
                                                                        id="nama_kurikulum" name="nama_kurikulum"
                                                                        value="{{ old('nama_kurikulum', $item->nama_kurikulum) }}">
                                                                    @error('nama_kurikulum')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="exampleTextarea1">Deskripsi</label>
                                                                    <textarea name="deskripsi" class="form-control" id="exampleTextarea1" rows="4">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                                                                    @error('deskripsi')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>
                                                                <div class="form-group">
                                                                    <label>Icon</label>
                                                                    <input type="file" name="icon"
                                                                        class="form-control  @error('icon') is-invalid @enderror"
                                                                        accept="image/*">
                                                                    @error('icon')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                    <small class="text-muted">Maximum file size: 2
                                                                        MB</small>
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

    <!-- Modal for adding new kurikulum -->
    <div class="modal fade" id="addKurikulumModal" tabindex="-1" aria-labelledby="addKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah kurikulum</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kurikulum.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nama_kurikulum">Nama kurikulum <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_kurikulum') is-invalid @enderror"
                                id="nama_kurikulum" name="nama_kurikulum" value="{{ old('nama_kurikulum') }}">
                            @error('nama_kurikulum')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea1">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="exampleTextarea1" rows="4">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label>Icon</label>
                            <input type="file" name="icon"
                                class="form-control  @error('icon') is-invalid @enderror" accept="image/*">

                            @error('icon')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="text-muted">Maximum file size: 2 MB</small>
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
