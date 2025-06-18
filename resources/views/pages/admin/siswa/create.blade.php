@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambah Data Siswa</h4>

                                    <form action="{{ route('siswa.store') }}" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <!-- Informasi Akun -->
                                        <h6 class="card-title">Informasi Akun</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputUsername">Username <span class="text-danger">*</span></label>
                                                    <input name="username"
                                                        class="form-control @error('username') is-invalid @enderror"
                                                        value="{{ old('username') }}" type="text"
                                                        id="exampleInputUsername" placeholder="Username">
                                                    @error('username')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputPassword">Password <span class="text-danger">*</span></label>
                                                    <input name="password"
                                                        class="form-control @error('password') is-invalid @enderror"
                                                        value="{{ old('password') }}" type="password"
                                                        id="exampleInputPassword" placeholder="Password">
                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputPasswordConfirmation">Konfirmasi
                                                        Password <span class="text-danger">*</span></label>
                                                    <input name="password_confirmation"
                                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                                        value="{{ old('password_confirmation') }}" type="password"
                                                        id="exampleInputPasswordConfirmation"
                                                        placeholder="Konfirmasi Password">
                                                    @error('password_confirmation')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <hr style="border: 1px solid #dee2e6; margin: 20px 0;">


                                        <h6 class="card-title">Informasi Pribadi</h6>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputName1">Nama Lengkap <span class="text-danger">*</span></label>
                                                    <input name="name"
                                                        class="form-control @error('name') is-invalid @enderror"
                                                        value="{{ old('name') }}" type="text" id="exampleInputName1"
                                                        placeholder="Name">
                                                    @error('name')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputName1">NIS</label>
                                                    <input name="nis"
                                                        class="form-control @error('nis') is-invalid @enderror"
                                                        value="{{ old('nis') }}" type="text" id="exampleInputnis1"
                                                        placeholder="nis">
                                                    @error('nis')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail3">Email address <span class="text-danger">*</span></label>
                                                    <input name="email"
                                                        class="form-control @error('email') is-invalid @enderror"
                                                        value="{{ old('email') }}" type="email" id="exampleInputEmail3"
                                                        placeholder="Email">
                                                    @error('email')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputName1">Tempat Lahir <span class="text-danger">*</span></label>
                                                    <input name="tempat_lahir"
                                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                                        value="{{ old('tempat_lahir') }}" type="text"
                                                        id="exampleInputtempat_lahir1" placeholder="Tempat Lahir">
                                                    @error('tempat_lahir')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="tanggalLahir">Tanggal Lahir <span class="text-danger">*</span></label>
                                                    <div class="input-group">
                                                        <input name="tanggal_lahir" id="tanggalLahir"
                                                            class="form-control @error('tanggal_lahir') is-invalid @enderror"
                                                            value="{{ old('tanggal_lahir') }}" type="text"
                                                            placeholder="Pilih Tanggal">
                                                        <div class="input-group-prepend">
                                                            <span class="input-group-text">
                                                                <i class="fas fa-calendar-alt" id="tanggalLahir"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    @error('tanggal_lahir')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleSelectGender">Jenis Kelamin <span class="text-danger">*</span></label>
                                                    <select name="jenis_kelamin"
                                                        class="form-control  @error('jenis_kelamin') is-invalid @enderror"
                                                        value="{{ old('jenis_kelamin') }}" id="exampleSelectGender">
                                                        <option value="">Pilih Jenis Kelamin</option>
                                                        <option value="Laki-Laki"
                                                            {{ old('jenis_kelamin') == 'Laki-Laki' ? 'selected' : '' }}>
                                                            Laki-Laki</option>
                                                        <option value="Perempuan"
                                                            {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>
                                                            Perempuan</option>
                                                    </select>
                                                    @error('jenis_kelamin')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>


                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputName1">Alamat <span class="text-danger">*</span></label>
                                                    <input name="alamat"
                                                        class="form-control @error('alamat') is-invalid @enderror"
                                                        value="{{ old('alamat') }}" type="text"
                                                        id="exampleInputalamat1" placeholder="Alamat">
                                                    @error('alamat')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleSelectKelas">Kelas <span class="text-danger">*</span></label>
                                                    <select name="kelas_id"
                                                        class="form-control @error('kelas_id') is-invalid @enderror"
                                                        id="exampleSelectKelas">
                                                        <option value="">Pilih Kelas</option>
                                                        @foreach ($kelas as $item)
                                                            <option value="{{ $item->id }}"
                                                                {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
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
                                            </div>


                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Foto</label>
                                                    <input type="file" name="foto"
                                                        class="form-control  @error('foto') is-invalid @enderror"
                                                        accept="image/*">

                                                    @error('foto')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                    <small class="text-muted">Maximum file size: 2 MB</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                            <a href="{{ route('siswa.index') }}" class="btn btn-light">Cancel</a>
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
