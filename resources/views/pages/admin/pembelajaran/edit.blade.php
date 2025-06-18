@extends('layouts.app')

@section('title', 'Data Pembelajaran')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Edit Data Pembelajaran</h4>

                                    <form action="{{ route('pembelajaran.update', $pembelajaran->id) }}" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        @csrf
                                        @method("PUT")

                                        <div class="form-group">
                                            <label for="exampleInputName1">Nama Mata Pelajaran <span class="text-danger">*</span></label>
                                            <input name="nama_mapel"
                                                class="form-control @error('nama_mapel') is-invalid @enderror"
                                                value="{{ old('nama_mapel', $pembelajaran->nama_mapel) }}"
                                                type="text" id="exampleInputnama_mapel1"
                                                placeholder="Nama Mata Pelajaran">
                                            @error('nama_mapel')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectKelas">Guru <span class="text-danger">*</span></label>
                                            <select name="guru_id"
                                                class="form-control @error('guru_id') is-invalid @enderror"
                                                id="exampleSelectguru">
                                                <option value="">Pilih guru</option>
                                                @foreach ($guru as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('guru_id', $pembelajaran->guru_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('guru_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectKelas">Kelas <span class="text-danger">*</span></label>
                                            <select name="kelas_id"
                                                class="form-control @error('kelas_id') is-invalid @enderror"
                                                id="exampleSelectKelas">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kelas as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('kelas_id', $pembelajaran->kelas_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kelas_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectKelas">Tahun Ajaran <span class="text-danger">*</span></label>
                                            <select name="tahun_ajaran_id"
                                                class="form-control @error('tahun_ajaran_id') is-invalid @enderror"
                                                id="exampleSelectKelas">
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($tahunAjaran as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('tahun_ajaran_id', $pembelajaran->tahun_ajaran_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_tahun }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('tahun_ajaran_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleSelectKelas">Kurikulum <span class="text-danger">*</span></label>
                                            <select name="kurikulum_id"
                                                class="form-control @error('kurikulum_id') is-invalid @enderror"
                                                id="exampleSelectkurikulum">
                                                <option value="">Pilih kurikulum</option>
                                                @foreach ($kurikulum as $item)
                                                    <option value="{{ $item->id }}"
                                                        {{ old('kurikulum_id', $pembelajaran->kurikulum_id) == $item->id ? 'selected' : '' }}>
                                                        {{ $item->nama_kurikulum }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kurikulum_id')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="semester">Semester <span class="text-danger">*</span></label>
                                            <select name="semester"
                                                class="form-control @error('semester') is-invalid @enderror"
                                                id="semester">
                                                <option value="">Pilih Semester</option>
                                                <option value="Ganjil"
                                                    {{ old('semester', $pembelajaran->semester) == 'Ganjil' ? 'selected' : '' }}>
                                                    Ganjil</option>
                                                <option value="Genap"
                                                    {{ old('semester', $pembelajaran->semester) == 'Genap' ? 'selected' : '' }}>
                                                    Genap</option>
                                            </select>
                                            @error('semester')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group">
                                            <label for="cover">Cover</label>
                                            <div class="upload-box">
                                                <input type="file" id="cover" name="cover" class="file-input">
                                                <div class="upload-area">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <p>Drag your file(s) or <span class="browse-text">browse</span></p>
                                                    <small>Max 10 MB files are allowed</small>
                                                </div>
                                            </div>
                                            @if($pembelajaran->cover)
                                                <p class="mt-2">Cover saat ini: <a href="{{ asset('storage/covers/' . $pembelajaran->cover) }}" target="_blank">Lihat Cover</a></p>
                                            @endif
                                        </div>                                        

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('pembelajaran.index') }}" class="btn btn-light">Cancel</a>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.querySelector(".file-input").addEventListener("change", function (e) {
        let fileName = e.target.files.length > 0 ? e.target.files[0].name : "Drag your file(s) or browse";
        document.querySelector(".upload-area p").innerHTML = fileName;
    });
</script>
@endpush
