@extends('layouts.app')

@section('title', 'Edit Data Guru')

@section('content')
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Edit Data Guru</h4>

                    <form action="{{ route('guru.update', $guru->id) }}" method="POST" class="forms-sample"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Informasi Akun -->
                        <h6 class="card-title">Informasi Akun</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input name="username" class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username', $guru->user->username) }}" type="text" id="username"
                                        placeholder="Username">
                                    @error('username')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password">Password (Kosongkan jika tidak ingin diubah)</label>
                                    <input name="password" class="form-control @error('password') is-invalid @enderror"
                                        type="password" id="password" placeholder="Password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="password_confirmation">Konfirmasi Password</label>
                                    <input name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        type="password" id="password_confirmation" placeholder="Konfirmasi Password">
                                    @error('password_confirmation')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr style="border: 1px solid #dee2e6; margin: 20px 0;">

                        <!-- Informasi Pribadi -->
                        <h6 class="card-title">Informasi Pribadi</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="name">Nama Lengkap</label>
                                    <input name="name" class="form-control @error('name') is-invalid @enderror"
                                        value="{{ old('name', $guru->user->name) }}" type="text" id="name"
                                        placeholder="Nama Lengkap">
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="nip">NIP</label>
                                    <input name="nip" class="form-control @error('nip') is-invalid @enderror"
                                        value="{{ old('nip', $guru->nip) }}" type="text" id="nip"
                                        placeholder="NIP">
                                    @error('nip')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input name="email" class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $guru->user->email) }}" type="email" id="email"
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
                                    <label for="tempat_lahir">Tempat Lahir</label>
                                    <input name="tempat_lahir"
                                        class="form-control @error('tempat_lahir') is-invalid @enderror"
                                        value="{{ old('tempat_lahir', $guru->tempat_lahir) }}" type="text"
                                        id="tempat_lahir" placeholder="Tempat Lahir">
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
                                            value="{{ old('tanggal_lahir', $guru->tanggal_lahir) }}" type="text"
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
                                    <label for="jenis_kelamin">Jenis Kelamin</label>
                                    <select name="jenis_kelamin"
                                        class="form-control @error('jenis_kelamin') is-invalid @enderror"
                                        id="jenis_kelamin">
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-Laki"
                                            {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Laki-Laki' ? 'selected' : '' }}>
                                            Laki-Laki</option>
                                        <option value="Perempuan"
                                            {{ old('jenis_kelamin', $guru->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>
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
                                    <label for="alamat">Alamat</label>
                                    <input name="alamat" class="form-control @error('alamat') is-invalid @enderror"
                                        value="{{ old('alamat', $guru->alamat) }}" type="text" id="alamat"
                                        placeholder="Alamat">
                                    @error('alamat')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <select name="status" class="form-control @error('status') is-invalid @enderror"
                                        id="status">
                                        <option value="">Pilih Status</option>
                                        <option value="PNS"
                                            {{ old('status', $guru->status) == 'PNS' ? 'selected' : '' }}>PNS</option>
                                        <option value="Honorer"
                                            {{ old('status', $guru->status) == 'Honorer' ? 'selected' : '' }}>Honorer
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
                                        class="form-control  @error('foto') is-invalid @enderror" accept="image/*">
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
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('guru.index') }}" class="btn btn-light ml-2">Batal</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
