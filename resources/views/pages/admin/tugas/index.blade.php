@extends('layouts.app')

@section('title', 'Data Tugas')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Tugas</p>
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
                                            <th>Materi</th>
                                            <th>Judul Tugas</th>
                                            <th>Deskripsi</th>
                                            <th>File</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($tugas as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                <td> {{ optional($item->materi)->judul ?? '-' }} </td>
                                                <td> {{ $item->judul }} </td>
                                                <td> {{ $item->deskripsi }} </td>
                                                <td>
                                                    @if ($item->file_path)
                                                        <div class="mt-2">
                                                            <a href="https://drive.google.com/file/d/{{ $item->file_path }}/view"
                                                                target="_blank">Lihat File</a>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete({{ $item->id }})">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm{{ $item->id }}" action="{{ route('tugas.destroy', $item->id) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editKelasModal{{ $item->id }}" tabindex="-1"
                                                aria-labelledby="editKelasModalLabel{{ $item->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-xl">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editKelasModalLabel{{ $item->id }}">
                                                                Edit Tugas
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="{{ route('tugas.update', $item->id) }}"
                                                            method="POST" enctype="multipart/form-data">
                                                            @csrf
                                                            @method('PUT')

                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="exampleSelectKelas">Materi <span class="text-danger">*</span></label>
                                                                    <select name="materi_id"
                                                                        class="form-control @error('materi_id') is-invalid @enderror"
                                                                        id="exampleSelectguru">
                                                                        <option value="">Pilih Materi</option>
                                                                        @foreach ($materi as $m)
                                                                            <option value="{{ $m->id }}"
                                                                                {{ old('materi_id', $item->materi_id) == $m->id ? 'selected' : '' }}>
                                                                                {{ $m->judul }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <!-- Judul -->
                                                                <div class="form-group">
                                                                    <label for="judul">Judul <span class="text-danger">*</span></label>
                                                                    <input type="text"
                                                                        class="form-control @error('judul') is-invalid @enderror"
                                                                        id="judul" name="judul"
                                                                        value="{{ old('judul', $item->judul) }}">
                                                                    @error('judul')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror
                                                                </div>

                                                                <!-- Deskripsi -->
                                                                <div class="form-group">
                                                                    <label for="deskripsi">Deskripsi</label>
                                                                    <textarea name="deskripsi" class="form-control" id="deskripsi" rows="4">{{ old('deskripsi', $item->deskripsi) }}</textarea>
                                                                    @if ($errors->has('deskripsi'))
                                                                        <div class="text-danger">
                                                                            {{ $errors->first('deskripsi') }}</div>
                                                                    @endif
                                                                </div>

                                                                <!-- File -->
                                                                <div class="form-group">
                                                                    <label for="file_path">File Tugas <span class="text-danger">*</span></label>
                                                                    <input type="file" id="file_path" name="file_path"
                                                                        class="form-control @error('file_path') is-invalid @enderror"
                                                                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,image/*">

                                                                    <small class="text-muted">Format yang diperbolehkan:
                                                                        PDF, DOC, PPT, XLS, ZIP, RAR, dan gambar.</small>
                                                                    <small class="text-muted d-block">Maximum file size: 10
                                                                        MB</small>

                                                                    @error('file_path')
                                                                        <span class="invalid-feedback" role="alert">
                                                                            <strong>{{ $message }}</strong>
                                                                        </span>
                                                                    @enderror

                                                                    <!-- Menampilkan file lama -->
                                                                    @if ($item->file_path)
                                                                        <div class="mt-2">
                                                                            <p>File saat ini: <a
                                                                                    href="https://drive.google.com/file/d/{{ $item->file_path }}/view"
                                                                                    target="_blank">Lihat File</a></p>
                                                                        </div>
                                                                    @endif
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

    <!-- Modal for adding new kelas -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content xl">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('tugas.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Materi <span class="text-danger">*</span></label>
                            <select name="materi_id" class="form-control @error('materi_id') is-invalid @enderror"
                                id="exampleSelectguru">
                                <option value="">Pilih Materi</option>
                                @foreach ($materi as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('materi_id') == $item->id ? 'selected' : '' }}>
                                        {{ $item->judul }}
                                    </option>
                                @endforeach
                            </select>
                            @error('materi_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="judul">Judul <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('judul') is-invalid @enderror"
                                id="judul" name="judul" value="{{ old('judul') }}">
                            @error('judul')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" id="exampleTextarea1" rows="4">{{ old('deskripsi') }}</textarea>
                            @if ($errors->has('deskripsi'))
                                <div class="text-danger">{{ $errors->first('deskripsi') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="file_path">File Tugas <span class="text-danger">*</span></label>
                            <input type="file" id="file_path" name="file_path"
                                class="form-control @error('file_path') is-invalid @enderror"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.zip,.rar,image/*">

                            <small class="text-muted">Format yang diperbolehkan: PDF, DOC, PPT, XLS, ZIP, RAR, dan
                                gambar.</small>
                            <small class="text-muted d-block">Maximum file size: 10 MB</small>

                            @error('file_path')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror

                            <!-- Menampilkan nama file yang dipilih -->
                            <div id="file-name" class="mt-2 text-muted"></div>
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

@push('scripts')
    <script>
        document.getElementById('file_path').addEventListener('change', function() {
            let fileName = this.files.length ? this.files[0].name : "Belum ada file yang dipilih.";
            document.getElementById('file-name').textContent = "File dipilih: " + fileName;
        });
    </script>
@endpush
