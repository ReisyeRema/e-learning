<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Siswa</title>
    <link rel="shortcut icon" href="{{ url('storage/logo_sekolah/' . $profilSekolah->foto) }}" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @include('includes.frontend.style-login')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container">
        <!-- Bagian kiri (gambar) -->
        <div class="image-section">
            <!-- Tombol Kembali -->
            <a href="{{ route('landing-page.index') }}" class="back-to-landing">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
            <img src="{{ asset('assets/frontend/landing-page/assets/img/login.png') }}" alt="Login Illustration">
        </div>

        <!-- Bagian kanan (form login) -->
        <div class="login-section">
            <div class="logo-wrapper">
                <img src="{{ url('storage/logo_sekolah/' . $profilSekolah->foto) }}" alt="Logo Sekolah"
                    class="logo-img">
            </div>
            <h2>Selamat Datang, Pelajar!</h2>
            <p>Silakan masuk untuk melanjutkan!</p>
            <form class="pt-3" method="POST" action="{{ route('login-siswa') }}">
                @csrf

                <div class="input-group">
                    <input name="id_user" :value="old('id_user')" type="text" placeholder="Email atau Username"
                        id="id_user" required>
                </div>

                <!-- Password with eye icon -->
                <div class="input-group password-container">
                    <input name="password" type="password" placeholder="Password" id="password" required>
                    <i class="fas fa-eye eye-icon" id="togglePassword"></i>
                </div>

                <div class="remember-forgot">
                    <label><input type="checkbox"> Ingat saya</label>
                    <a href="{{ route('password.request') }}"><i class="fas fa-key"></i> Lupa Password?</a>
                </div>

                <button type="submit" class="login-btn mb-5">Masuk</button>

            </form>

            <div class="remember-forgot">
                <span class="ml-10 mr-3">Belum Punya Akun?</span>
                <a href="{{ route('register') }}">Buat Akun</a>
            </div>

            <footer class="login-footer">
                <p>&copy; {{ date('Y') }} {{ $profilSekolah->nama_sekolah ?? 'Nama Sekolah' }}. All rights reserved.
                </p>
            </footer>
        </div>
    </div>

    <script>
        // JavaScript untuk men-toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            // Cek tipe input dan toggle antara password dan text
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                togglePassword.classList.remove('fa-eye');
                togglePassword.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                togglePassword.classList.remove('fa-eye-slash');
                togglePassword.classList.add('fa-eye');
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if ($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    html: '<p style="color: #555;">{{ $errors->first() }}</p>',
                    confirmButtonText: '<i class="fas fa-redo"></i> Coba Lagi',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal-btn-red'
                    }
                });
            @endif

            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    html: '<p style="color: #555;">{{ session('success') }}</p>',
                    confirmButtonText: '<i class="fas fa-check"></i> Oke',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal-btn-green'
                    }
                });
            @endif
        });
    </script>

</body>

</html>
