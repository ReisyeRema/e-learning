<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Siswa</title>
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
            <h2>Registrasi Akun</h2>
            <p>Silakan daftar untuk membuat akun!</p>
            <form class="pt-3" method="POST" action="{{ route('register') }}">
                @csrf

                <div class="input-group">
                    <input name="nis" value="{{ old('nis') }}" type="text"
                        placeholder="NIS (Nomor Induk Siswa)">
                </div>

                <div class="input-group">
                    <input name="email" value="{{ old('email') }}" type="email" placeholder="Email">
                </div>

                <div class="input-group">
                    <input name="username" value="{{ old('username') }}" type="text" placeholder="Username">
                </div>

                <div class="input-group password-container">
                    <input name="password" type="password" placeholder="Password" id="password">
                    <i class="fas fa-eye eye-icon" id="togglePassword"></i>
                </div>

                <div class="input-group password-container">
                    <input name="password_confirmation" type="password" placeholder="Konfirmasi Password" id="confirmPassword">
                    <i class="fas fa-eye eye-icon" id="toggleConfirmPassword"></i>
                </div>

                <button type="submit" class="login-btn">Daftar</button>

                <div class="remember-forgot">
                    <span class="ml-10 mr-3">Sudah Punya Akun?</span>
                    <a href="{{ route('login-siswa') }}">Masuk Disini</a>
                </div>
            </form>

            <footer class="login-footer">
                <p>&copy; {{ date('Y') }} {{ $profilSekolah->nama_sekolah ?? 'Nama Sekolah' }}. All rights
                    reserved.</p>
            </footer>
        </div>
    </div>

    {{-- <script>
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
    </script> --}}

    <script>
        // Toggle password
        const togglePassword = document.getElementById('togglePassword');
        const passwordField = document.getElementById('password');
    
        togglePassword.addEventListener('click', function () {
            const type = passwordField.type === 'password' ? 'text' : 'password';
            passwordField.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    
        // Toggle confirm password
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        const confirmPasswordField = document.getElementById('confirmPassword');
    
        toggleConfirmPassword.addEventListener('click', function () {
            const type = confirmPasswordField.type === 'password' ? 'text' : 'password';
            confirmPasswordField.type = type;
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
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
        });
    </script>

</body>

</html>
