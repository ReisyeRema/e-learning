@extends('layouts.app')

@section('title', 'Data Kuis')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Kuis</p>
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
                                            <th>Judul</th>
                                            <th>Kategori</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($kuis as $item)
                                            <tr>
                                                <td style="text-align: center"> {{ $loop->iteration }} </td>
                                                {{-- <td> {{ optional($item->materi)->judul }} </td> --}}
                                                <td> {{ $item->judul }} </td>
                                                <td> {{ $item->kategori }} </td>
                                                <td>
                                                    <div class="d-flex align-items-center">

                                                        <!-- Button to navigate to another page -->
                                                        <a href="{{ route('soal.index', ['kuis_id' => $item->id]) }}"
                                                            class="btn btn-sm btn-outline-primary btn-fw mr-3">
                                                            +
                                                        </a>

                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal{{ $item->id }}">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                            onclick="confirmDelete({{ $item->id }})">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm{{ $item->id }}"
                                                            action="{{ route('kuis.destroy', $item->id) }}" method="POST"
                                                            style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
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
                                                        <form action="{{ route('kuis.update', $item->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PUT')
                                                            <div class="modal-body">

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

                                                                <div class="form-group">
                                                                    <label
                                                                        for="kategori-{{ $item->id }}">Kategori <span class="text-danger">*</span></label>
                                                                    <select name="kategori"
                                                                        class="form-control kategori-edit"
                                                                        id="kategori-{{ $item->id }}">
                                                                        <option value="">Pilih Kategori</option>
                                                                        <option value="Kuis"
                                                                            {{ old('kategori', $item->kategori) == 'Kuis' ? 'selected' : '' }}>
                                                                            Kuis</option>
                                                                        <option value="Ujian Mid"
                                                                            {{ old('kategori', $item->kategori) == 'Ujian Mid' ? 'selected' : '' }}>
                                                                            Ujian Mid</option>
                                                                        <option value="Ujian Akhir"
                                                                            {{ old('kategori', $item->kategori) == 'Ujian Akhir' ? 'selected' : '' }}>
                                                                            Ujian Akhir</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group materi-form-group"
                                                                    id="materi-form-group-{{ $item->id }}">
                                                                    <label for="materi-{{ $item->id }}">Materi <span class="text-danger">*</span></label>
                                                                    <select name="materi_id" class="form-control"
                                                                        id="materi-{{ $item->id }}">
                                                                        <option value="">Pilih Materi</option>
                                                                        @foreach ($materi as $m)
                                                                            <option value="{{ $m->id }}"
                                                                                {{ old('materi_id', $item->materi_id) == $m->id ? 'selected' : '' }}>
                                                                                {{ $m->judul }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kuis</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('kuis.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">

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
                            <label for="kategori">Kategori <span class="text-danger">*</span></label>
                            <select name="kategori" class="form-control @error('kategori') is-invalid @enderror"
                                id="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="Kuis" {{ old('kategori') == 'Kuis' ? 'selected' : '' }}>Kuis</option>
                                <option value="Ujian Mid" {{ old('kategori') == 'Ujian Mid' ? 'selected' : '' }}>Ujian Mid
                                </option>
                                <option value="Ujian Akhir" {{ old('kategori') == 'Ujian Akhir' ? 'selected' : '' }}>Ujian
                                    Akhir</option>
                            </select>
                            @error('kategori')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group" id="materi-form-group">
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
        $(document).ready(function() {
            function toggleMateriForm() {
                let kategori = $('#kategori').val();
                if (kategori === 'Kuis') {
                    $('#materi-form-group').show();
                } else {
                    $('#materi-form-group').hide();
                    $('#exampleSelectguru').val('');
                }
            }

            // Jalankan saat halaman dibuka
            toggleMateriForm();

            // Jalankan saat dropdown berubah
            $('#kategori').on('change', function() {
                toggleMateriForm();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk show/hide materi berdasarkan kategori
            function toggleEditMateriForm(id) {
                const kategori = $(`#kategori-${id}`).val();
                const materiGroup = $(`#materi-form-group-${id}`);
                const materiSelect = $(`#materi-${id}`);

                if (kategori === 'Kuis') {
                    materiGroup.show();
                } else {
                    materiGroup.hide();
                    materiSelect.val('');
                }
            }

            // Loop semua modal edit dan inisialisasi berdasarkan data
            @foreach ($kuis as $item)
                toggleEditMateriForm({{ $item->id }});
                $(`#kategori-{{ $item->id }}`).on('change', function() {
                    toggleEditMateriForm({{ $item->id }});
                });
            @endforeach
        });
    </script>
@endpush
