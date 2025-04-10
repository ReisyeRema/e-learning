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
                                    <h4 class="card-title">Tambah Data Pembelajaran</h4>

                                    <form action="{{ route('soal.store', ['kuis_id' => $kuis->id]) }}" method="POST" class="forms-sample">
                            
                                        @csrf

                                        <div class="form-group">
                                            <label for="exampleInputName1">Nama Mata Pelajaran</label>
                                            <input name="nama_mapel"
                                                class="form-control @error('nama_mapel') is-invalid @enderror"
                                                value="{{ old('nama_mapel') }}" type="text"
                                                id="exampleInputnama_mapel1" placeholder="Tempat Lahir">
                                            @error('nama_mapel')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('soal.index', ['kuis_id' => $kuis->id]) }}" class="btn btn-light">Cancel</a>
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