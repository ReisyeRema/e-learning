<section>
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Informasi Akun</h2>
                <p class="card-description">
                    Perbarui informasi profil akun dan alamat email Anda.
                </p>

                <!-- Form Verifikasi Email -->
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>

                @if ($user->hasRole('Super Admin'))
                    <form class="forms-sample" method="post" action="{{ route('profile-super-admin.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <!-- Gambar Profil dengan Bingkai -->
                        <div class="d-flex flex-column align-items-center">
                            <div class="profile-picture">
                                <img id="previewFoto"
                                    src="{{ $user->foto ? asset('storage/foto_user/' . $user->foto) : asset('assets/img/profil.png') }}"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 150px; max-height: 150px;">
                            </div>
                            <button type="button" class="btn btn-link text-primary"
                                onclick="document.getElementById('uploadFoto').click();">
                                <h6>Ganti Foto Profil</h6>
                            </button>
                            <input id="uploadFoto" name="foto" type="file" accept="image/*" class="d-none"
                                onchange="previewImage(event)">
                        </div>

                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-center">
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div>
                                        <h4 class="text-sm mt-2 text-blue-800 dark:text-gray-200">
                                            {{ __('Alamat email Anda belum diverifikasi') }}
                                        </h4>
                                        <div class="mt-3 mb-4">
                                            <button form="send-verification" class="btn btn-sm btn-outline-info btn-fw">
                                                {{ __('Klik Untuk Verifikasi') }}
                                            </button>
                                        </div>
                                        @if (session('status') === 'verification-link-sent')
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    showSuccessAlert('Tautan verifikasi baru telah dikirim ke alamat email Anda.');
                                                });
                                            </script>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Input untuk Nama -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" type="text" class="form-control"
                                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Input untuk Email -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input id="email" name="email" type="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Input untuk Username -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input id="username" name="username" type="text" class="form-control"
                                    value="{{ old('username', $user->username) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex items-center gap-4">
                            <button class="btn btn-primary mr-2">{{ __('Save') }}</button>

                            @if (session('status') === 'profile-updated')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        showSuccessAlert('Profile berhasil diperbarui!');
                                    });
                                </script>
                            @endif
                        </div>
                    </form>
                @endif

                @if ($user->hasRole('Admin'))
                    <form class="forms-sample" method="post" action="{{ route('profile-admin.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <!-- Gambar Profil dengan Bingkai -->
                        <div class="d-flex flex-column align-items-center">
                            <div class="profile-picture">
                                <img id="previewFoto"
                                    src="{{ $user->foto ? asset('storage/foto_user/' . $user->foto) : asset('assets/img/profil.png') }}"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 150px; max-height: 150px;">
                            </div>
                            <button type="button" class="btn btn-link text-primary"
                                onclick="document.getElementById('uploadFoto').click();">
                                <h6>Ganti Foto Profil</h6>
                            </button>
                            <input id="uploadFoto" name="foto" type="file" accept="image/*" class="d-none"
                                onchange="previewImage(event)">
                        </div>

                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-center">
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div>
                                        <h4 class="text-sm mt-2 text-blue-800 dark:text-gray-200">
                                            {{ __('Alamat email Anda belum diverifikasi') }}
                                        </h4>
                                        <div class="mt-3 mb-4">
                                            <button form="send-verification" class="btn btn-sm btn-outline-info btn-fw">
                                                {{ __('Klik Untuk Verifikasi') }}
                                            </button>
                                        </div>
                                        @if (session('status') === 'verification-link-sent')
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    showSuccessAlert('Tautan verifikasi baru telah dikirim ke alamat email Anda.');
                                                });
                                            </script>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Input untuk Nama -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" type="text" class="form-control"
                                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Input untuk Email -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input id="email" name="email" type="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Input untuk Username -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input id="username" name="username" type="text" class="form-control"
                                    value="{{ old('username', $user->username) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex items-center gap-4">
                            <button class="btn btn-primary mr-2">{{ __('Save') }}</button>

                            @if (session('status') === 'profile-updated')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        showSuccessAlert('Profile berhasil diperbarui!');
                                    });
                                </script>
                            @endif
                        </div>
                    </form>
                @endif

                @if ($user->hasRole('Guru'))
                    <form class="forms-sample" method="post" action="{{ route('profile-guru.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('put')

                        <!-- Gambar Profil dengan Bingkai -->
                        <div class="d-flex flex-column align-items-center">
                            <div class="profile-picture">
                                <img id="previewFoto"
                                    src="{{ $user->foto ? asset('storage/foto_user/' . $user->foto) : asset('assets/img/profil.png') }}"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 150px; max-height: 150px;">
                            </div>
                            <button type="button" class="btn btn-link text-primary"
                                onclick="document.getElementById('uploadFoto').click();">
                                <h6>Ganti Foto Profil</h6>
                            </button>
                            <input id="uploadFoto" name="foto" type="file" accept="image/*" class="d-none"
                                onchange="previewImage(event)">
                        </div>

                        <div class="d-flex justify-content-center align-items-center">
                            <div class="text-center">
                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                                    <div>
                                        <h4 class="text-sm mt-2 text-blue-800 dark:text-gray-200">
                                            {{ __('Alamat email Anda belum diverifikasi') }}
                                        </h4>
                                        <div class="mt-3 mb-4">
                                            <button form="send-verification"
                                                class="btn btn-sm btn-outline-info btn-fw">
                                                {{ __('Klik Untuk Verifikasi') }}
                                            </button>
                                        </div>
                                        @if (session('status') === 'verification-link-sent')
                                            <script>
                                                document.addEventListener('DOMContentLoaded', function() {
                                                    showSuccessAlert('Tautan verifikasi baru telah dikirim ke alamat email Anda.');
                                                });
                                            </script>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Input untuk Nama -->
                        <div class="form-group row">
                            <label for="name" class="col-sm-3 col-form-label">Name</label>
                            <div class="col-sm-9">
                                <input id="name" name="name" type="text" class="form-control"
                                    value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('name')" />
                        </div>

                        <!-- Input untuk Email -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-9">
                                <input id="email" name="email" type="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('email')" />
                        </div>

                        <!-- Input untuk Username -->
                        <div class="form-group row">
                            <label for="email" class="col-sm-3 col-form-label">Username</label>
                            <div class="col-sm-9">
                                <input id="username" name="username" type="text" class="form-control"
                                    value="{{ old('username', $user->username) }}" required autocomplete="username">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('username')" />
                        </div>

                        <h4 class="card-title mt-4">Profil Guru</h4>

                        <!-- NIP -->
                        <div class="form-group row">
                            <label for="nip" class="col-sm-3 col-form-label">NIP</label>
                            <div class="col-sm-9">
                                <input id="nip" name="nip" type="text" class="form-control"
                                    value="{{ old('nip', $profile->nip ?? '') }}">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                        </div>

                        <!-- Tempat Lahir -->
                        <div class="form-group row">
                            <label for="tempat_lahir" class="col-sm-3 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-9">
                                <input id="tempat_lahir" name="tempat_lahir" type="text" class="form-control"
                                    value="{{ old('tempat_lahir', $profile->tempat_lahir ?? '') }}">
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('tempat_lahir')" />
                        </div>

                        <!-- Tanggal Lahir -->
                        <div class="form-group row">
                            <label for="tanggalLahir" class="col-sm-3 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-9">
                                <div class="input-group">
                                    <input id="tanggalLahir" name="tanggal_lahir" type="text"
                                        class="form-control"
                                        value="{{ old('tanggal_lahir', $profile->tanggal_lahir ?? '') }}"
                                        placeholder="YYYY-MM-DD">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar-alt" id="tanggalLahir"></i>
                                        </span>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
                            </div>
                        </div>

                        <!-- Jenis Kelamin -->
                        <div class="form-group row">
                            <label for="jenis_kelamin" class="col-sm-3 col-form-label">Jenis Kelamin</label>
                            <div class="col-sm-9">
                                <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                                    <option value="Laki-laki"
                                        {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                        Laki-laki</option>
                                    <option value="Perempuan"
                                        {{ old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                        Perempuan</option>
                                </select>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('jenis_kelamin')" />
                        </div>

                        <!-- Alamat -->
                        <div class="form-group row">
                            <label for="alamat" class="col-sm-3 col-form-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea id="alamat" name="alamat" class="form-control">{{ old('alamat', $profile->alamat ?? '') }}</textarea>
                            </div>
                            <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                        </div>

                        <!-- Tombol Simpan -->
                        <div class="flex items-center gap-4">
                            <button class="btn btn-primary mr-2">{{ __('Save') }}</button>

                            @if (session('status') === 'profile-updated')
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        showSuccessAlert('Profile berhasil diperbarui!');
                                    });
                                </script>
                            @endif
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</section>
