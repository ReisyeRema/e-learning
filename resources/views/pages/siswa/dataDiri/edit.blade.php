@extends('layouts.main')

@section('title', 'Data Diri')

@section('content')
    <!-- Data Diri Section -->
    <div class="data-diri-box">
        <h3>Data Diri</h3>
        <form action="{{ route('profile-siswa.update') }}" method="post" enctype="multipart/form-data">

            @csrf
            @method('put')

            <div class="d-flex flex-column align-items-center mb-3">
                <div class="profile-picture">
                    <img id="previewFoto"
                        src="{{ $user->foto ? asset('storage/foto_user/' . $user->foto) : asset('assets/img/profil.png') }}"
                        alt="Foto Profil" class="rounded-circle img-fluid" style="max-width: 150px; max-height: 150px;">
                </div>

                <button type="button" class="btn btn-sm btn-outline-success"
                    onclick="document.getElementById('uploadFoto').click();">
                    Ganti Foto Profil
                </button>

                <input id="uploadFoto" name="foto" type="file" accept="image/*" class="d-none"
                    onchange="previewImage(event)">
            </div>


            <div class="row mb-3">
                <label for="name" class="col-md-3 col-form-label text-left">Nama</label>
                <div class="col-md-9">
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="username" class="col-md-3 col-form-label text-left">Username</label>
                <div class="col-md-9">
                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-md-3 col-form-label text-left">Email</label>
                <div class="col-md-9">
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="nis" class="col-md-3 col-form-label text-left">NIS</label>
                <div class="col-md-9">
                    <input id="nis" name="nis" type="text" value="{{ old('nis', $profile->nis ?? '') }}"
                        class="form-control" disabled>
                </div>
            </div>

            <div class="row mb-3">
                <label for="kelas_id" class="col-md-3 col-form-label text-left">Kelas</label>
                <div class="col-md-9">
                    <select name="kelas_id" class="form-control @error('kelas_id') is-invalid @enderror" id="kelas_id" disabled>
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kelas_id', $profile->kelas_id ?? '') == $item->id ? 'selected' : '' }}>
                                {{ $item->nama_kelas }} - {{ $item->tahun_ajaran }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="tempat_lahir" class="col-md-3 col-form-label text-left">Tempat Lahir</label>
                <div class="col-md-9">
                    <input id="tempat_lahir" name="tempat_lahir" type="text"
                        value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}" class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="tanggalLahir" class="col-md-3 col-form-label text-left">Tanggal Lahir</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <input id="tanggalLahir" name="tanggal_lahir" type="text"
                            value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}" class="form-control"
                            placeholder="YYYY-MM-DD">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="jenis_kelamin" class="col-md-3 col-form-label text-left">Jenis Kelamin</label>
                <div class="col-md-9">
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki"
                            {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                            Laki-laki</option>
                        <option value="Perempuan"
                            {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                            Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="alamat" class="col-md-3 col-form-label text-left">Alamat</label>
                <div class="col-md-9">
                    <textarea id="alamat" name="alamat" class="form-control">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                </div>
            </div>


            <div class="col-md-12 text-end mt-3">

                <button class="btn btn-success mr-2">{{ __('Save') }}</button>

                @if (session('status') === 'profile-updated')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessAlert('Profile berhasil diperbarui!');
                        });
                    </script>
                @endif
            </div>
        </form>
    </div>

    <!-- Ubah Password Section -->
    <div class="data-diri-box mt-5">
        <h3 class="mb-5">Informasi Akun</h3>

        <form action="{{ route('password.update') }}" method="post" enctype="multipart/form-data">

            @csrf
            @method('put')

            <div class="col-md-12 mb-3">
                <label for="current_password" class="pb-2">Password Lama</label>
                <div class="input-group">
                    <input name="current_password" type="password" class="form-control"
                        id="update_password_current_password">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->updatePassword->has('current_password'))
                    <span class="text-danger mt-2 d-block">
                        {{ $errors->updatePassword->first('current_password') }}
                    </span>
                @endif
            </div>

            <div class="col-md-12 mb-3">
                <label for="password" class="pb-2">Password Baru</label>
                <div class="input-group">
                    <input id="update_password_password" name="password" type="password" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->updatePassword->has('password'))
                    <span class="text-danger mt-2 d-block">
                        {{ $errors->updatePassword->first('password') }}
                    </span>
                @endif
            </div>

            <div class="col-md-12 mb-3">
                <label for="password_confirmation" class="pb-2">Konfirmasi Password</label>
                <div class="input-group">
                    <input name="password_confirmation" type="password" class="form-control"
                        id="update_password_password_confirmation">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                @if ($errors->updatePassword->has('password_confirmation'))
                    <span class="text-danger mt-2 d-block">
                        {{ $errors->updatePassword->first('password_confirmation') }}
                    </span>
                @endif
            </div>



            <div class="col-md-12 text-end mt-3">

                <button class="btn btn-success mr-2">{{ __('Save') }}</button>

                @if (session('status') === 'password-updated')
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessAlert('Password berhasil diperbarui!');
                        });
                    </script>
                @endif
            </div>
        </form>

    </div>


@endsection
