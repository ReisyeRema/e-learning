@extends('layouts.app')

@section('title', 'Data Tugas')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header Section -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4>Daftar Soal - {{ $kuis->judul }}</h4>

                                <div class="d-flex flex-column align-items-end">
                                    <!-- Tombol Tambah Soal -->
                                    <button type="button" class="btn btn-sm btn-dark mb-2" data-toggle="modal"
                                        data-target="#addKelasModal">
                                        <i class="fas fa-plus"></i>
                                    </button>

                                    <!-- Tombol Export dan Import -->
                                    <div class="d-flex">
                                        <a href="{{ route('export.template.soal') }}" class="btn btn-sm btn-primary mr-2">
                                            <i class="fas fa-file-export mr-1"></i> Export Template Soal
                                        </a>

                                        <button type="button" class="btn btn-sm btn-success" data-toggle="modal"
                                            data-target="#importModal">
                                            <i class="fas fa-file-import mr-1"></i> Import Soal
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <!-- List Soal -->
                            <div class="list-group">
                                @foreach ($soalKuis as $item)
                                    <div
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 shadow-sm rounded mb-2">
                                        <!-- Info Soal -->
                                        <div class="d-flex align-items-center">
                                            <!-- Nomor -->
                                            <div class="d-flex justify-content-center align-items-center rounded bg-primary text-white font-weight-bold"
                                                style="width: 40px; height: 40px; font-size: 18px;">
                                                {{ $loop->iteration }}
                                            </div>
                                            <!-- Detail -->
                                            <div class="ml-3">
                                                <p class="text-muted small mb-1">{{ Str::limit($item->teks_soal, 50) }}</p>
                                                <span class="badge badge-light">{{ $item->type_soal }}</span>
                                            </div>
                                        </div>

                                        <!-- Aksi -->
                                        <div class="d-flex">
                                            <button class="btn btn-light btn-sm mr-2" data-toggle="modal"
                                                data-target="#editKelasModal{{ $item->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm" data-toggle="modal"
                                                data-target="#deleteKelasModal{{ $item->id }}"
                                                onclick="confirmDelete({{ $item->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="deleteForm{{ $item->id }}"
                                                action="{{ route('soal.destroy', $item->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editKelasModal{{ $item->id }}" tabindex="-1"
                                        aria-labelledby="editKelasModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Soal</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form action="{{ route('soal.update', $item->id) }}" method="POST"
                                                    enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')

                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="teks_soal">Teks Soal <span
                                                                    class="text-danger">*</span></label>
                                                            <textarea class="form-control" name="teks_soal" required>{{ $item->teks_soal }}</textarea>
                                                        </div>

                                                        <input type="hidden" name="kuis_id" value="{{ $kuis->id }}">

                                                        <div class="form-group">
                                                            <label for="gambar">Unggah Gambar (Opsional)</label>
                                                            <input type="file" class="form-control" name="gambar"
                                                                accept="image/*">

                                                            <!-- Pratinjau gambar jika sudah ada -->
                                                            @if ($item->gambar)
                                                                <p class="mt-2">Gambar Saat Ini:</p>
                                                                <img src="{{ asset('storage/' . $item->gambar) }}"
                                                                    alt="Gambar Soal" class="img-fluid mt-2"
                                                                    style="max-width: 100px;">
                                                            @endif

                                                        </div>

                                                        <div class="form-group">
                                                            <label for="type_soal">Tipe Soal <span
                                                                    class="text-danger">*</span></label>
                                                            <select name="type_soal" class="form-control type-soal"
                                                                data-id="{{ $item->id }}">
                                                                <option value="Objective"
                                                                    {{ $item->type_soal == 'Objective' ? 'selected' : '' }}>
                                                                    Objective</option>
                                                                <option value="Essay"
                                                                    {{ $item->type_soal == 'Essay' ? 'selected' : '' }}>
                                                                    Essay</option>
                                                                <option value="TrueFalse"
                                                                    {{ $item->type_soal == 'TrueFalse' ? 'selected' : '' }}>
                                                                    True or False</option>
                                                            </select>
                                                        </div>

                                                        <div class="soal-fields" id="soal_fields_{{ $item->id }}">
                                                            @if ($item->type_soal == 'Objective')
                                                                @php $choices = $item->pilihan_jawaban; @endphp
                                                                <div class="form-group">
                                                                    <label>Pilihan Jawaban <span
                                                                            class="text-danger">*</span></label>
                                                                    <input type="text" name="pilihan_jawaban[A]"
                                                                        class="form-control mb-2" placeholder="Jawaban A"
                                                                        value="{{ $choices['A'] ?? '' }}">
                                                                    <input type="text" name="pilihan_jawaban[B]"
                                                                        class="form-control mb-2" placeholder="Jawaban B"
                                                                        value="{{ $choices['B'] ?? '' }}">
                                                                    <input type="text" name="pilihan_jawaban[C]"
                                                                        class="form-control mb-2" placeholder="Jawaban C"
                                                                        value="{{ $choices['C'] ?? '' }}">
                                                                    <input type="text" name="pilihan_jawaban[D]"
                                                                        class="form-control mb-2" placeholder="Jawaban D"
                                                                        value="{{ $choices['D'] ?? '' }}">
                                                                    <input type="text" name="pilihan_jawaban[E]"
                                                                        class="form-control mb-2" placeholder="Jawaban E"
                                                                        value="{{ $choices['E'] ?? '' }}">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Jawaban Benar <span
                                                                            class="text-danger">*</span></label>
                                                                    <select name="jawaban_benar" class="form-control">
                                                                        <option value="A"
                                                                            {{ $item->jawaban_benar == 'A' ? 'selected' : '' }}>
                                                                            A</option>
                                                                        <option value="B"
                                                                            {{ $item->jawaban_benar == 'B' ? 'selected' : '' }}>
                                                                            B</option>
                                                                        <option value="C"
                                                                            {{ $item->jawaban_benar == 'C' ? 'selected' : '' }}>
                                                                            C</option>
                                                                        <option value="D"
                                                                            {{ $item->jawaban_benar == 'D' ? 'selected' : '' }}>
                                                                            D</option>
                                                                        <option value="E"
                                                                            {{ $item->jawaban_benar == 'E' ? 'selected' : '' }}>
                                                                            E</option>
                                                                    </select>
                                                                </div>
                                                            @elseif ($item->type_soal == 'Essay')
                                                                <div class="form-group">
                                                                    <label>Jawaban Essay</label>
                                                                    <textarea name="jawaban_benar" class="form-control" rows="3">{{ $item->jawaban_benar }}</textarea>
                                                                </div>
                                                            @elseif ($item->type_soal == 'TrueFalse')
                                                                <div class="form-group">
                                                                    <label>Jawaban Benar</label>
                                                                    <select name="jawaban_benar" class="form-control">
                                                                        <option value="true"
                                                                            {{ $item->jawaban_benar == 'true' ? 'selected' : '' }}>
                                                                            Benar</option>
                                                                        <option value="false"
                                                                            {{ $item->jawaban_benar == 'false' ? 'selected' : '' }}>
                                                                            Salah</option>
                                                                    </select>
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="{{ route('soal.store', ['kuis_id' => $kuis->id]) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">

                        <input type="hidden" name="kuis_id" value="{{ $kuis->id }}">


                        <div class="form-group">
                            <label for="teks_soal">Teks Soal <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="teks_soal">{{ old('teks_soal') }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Unggah Gambar (Opsional)</label>
                            <input type="file" class="form-control @error('gambar') is-invalid @enderror"
                                name="gambar" accept="image/*">
                            @error('gambar')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectGender">Tipe Soal <span class="text-danger">*</span></label>
                            <select name="type_soal" class="form-control  @error('type_soal') is-invalid @enderror"
                                value="{{ old('type_soal') }}" id="type_soal">
                                <option value="">Pilih Type Soal</option>
                                <option value="Objective" {{ old('type_soal') == 'Objective' ? 'selected' : '' }}>
                                    Objective</option>
                                <option value="Essay" {{ old('type_soal') == 'Essay' ? 'selected' : '' }}>
                                    Essay</option>
                                <option value="TrueFalse" {{ old('type_soal') == 'TrueFalse' ? 'selected' : '' }}>
                                    True or False</option>
                            </select>
                            @error('type_soal')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div id="soal_fields"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Import Soal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('soal.import', $kuis->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">Import Soal dari Excel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">Pilih File Excel <span class="text-danger">*</span></label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="file" name="file"
                                    accept=".xls,.xlsx" required>
                                <label class="custom-file-label" for="file">Pilih file Excel...</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-file-import mr-1"></i>
                            Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('type_soal').addEventListener('change', function() {
                let fieldContainer = document.getElementById('soal_fields');
                fieldContainer.innerHTML = '';

                if (this.value === 'Objective') {
                    fieldContainer.innerHTML = `
                    <div class="form-group">
                        <label>Pilihan Jawaban <span class="text-danger">*</span></label>
                        <input type="text" name="pilihan_jawaban[A]" class="form-control mb-2" placeholder="Jawaban A">
                        <input type="text" name="pilihan_jawaban[B]" class="form-control mb-2" placeholder="Jawaban B">
                        <input type="text" name="pilihan_jawaban[C]" class="form-control mb-2" placeholder="Jawaban C">
                        <input type="text" name="pilihan_jawaban[D]" class="form-control mb-2" placeholder="Jawaban D">
                        <input type="text" name="pilihan_jawaban[E]" class="form-control mb-2" placeholder="Jawaban E">
                    </div>

                    <div class="form-group">
                        <label>Jawaban Benar <span class="text-danger">*</span></label>
                        <select name="jawaban_benar" class="form-control">
                            <option value="">Pilih Jawaban Benar</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                            <option value="E">E</option>
                        </select>
                    </div>
                `;
                } else if (this.value === 'Essay') {
                    fieldContainer.innerHTML =
                        `<div class="form-group">
                        <label>Jawaban Benar <span class="text-danger">*</span></label>
                        <textarea name="jawaban_benar" class="form-control" rows="3" placeholder="Jawaban Essay"></textarea></div>`;
                } else if (this.value === 'TrueFalse') {
                    fieldContainer.innerHTML = `
                    <div class="form-group">
                    <label>Jawaban Benar <span class="text-danger">*</span></label>
                    <select name="jawaban_benar" class="form-control">
                        <option value="true">Benar</option>
                        <option value="false">Salah</option>
                    </select>
                    </div>
                `;
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // update label nama file
            $('.custom-file-input').on('change', function() {
                let fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').html(fileName);
            });
        });
    </script>
@endpush
