@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Tambah Data Guru</h4>

                                    <form action="{{ route('guru.store') }}" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        @csrf

                                        <!-- Informasi Akun -->
                                        <h6 class="card-title">Informasi Akun</h6>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputUsername">Username</label>
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
                                                    <label for="exampleInputPassword">Password</label>
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
                                                        Password</label>
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
                                                    <label for="exampleInputName1">Nama Lengkap</label>
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
                                                    <label for="exampleInputName1">NIP</label>
                                                    <input name="nip"
                                                        class="form-control @error('nip') is-invalid @enderror"
                                                        value="{{ old('nip') }}" type="text" id="exampleInputnip1"
                                                        placeholder="Nip">
                                                    @error('nip')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="exampleInputEmail3">Email address</label>
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
                                                    <label for="exampleInputName1">Tempat Lahir</label>
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
                                                    <label for="tanggalLahir">Tanggal Lahir</label>
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
                                                    <label for="exampleSelectGender">Jenis Kelamin</label>
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
                                                    <label for="exampleInputName1">Alamat</label>
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
                                                    <label for="exampleSelectGender">Status</label>
                                                    <select name="status"
                                                        class="form-control  @error('status') is-invalid @enderror"
                                                        value="{{ old('status') }}" id="exampleSelectGender">
                                                        <option value="">Pilih Status</option>
                                                        <option value="PNS"
                                                            {{ old('status') == 'PNS' ? 'selected' : '' }}>PNS</option>
                                                        <option value="Honorer"
                                                            {{ old('status') == 'Honorer' ? 'selected' : '' }}>Honorer
                                                        </option>
                                                    </select>
                                                    @error('status')
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
                                            <a href="{{ route('guru.index') }}" class="btn btn-light">Cancel</a>
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
