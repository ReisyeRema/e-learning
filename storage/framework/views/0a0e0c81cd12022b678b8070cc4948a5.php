<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Siswa</title>
    <link rel="shortcut icon" href="<?php echo e(url('storage/logo_sekolah/' . $profilSekolah->foto)); ?>" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <?php echo $__env->make('includes.frontend.style-login', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
</head>

<body>
    <div class="container">
        <!-- Bagian kiri (gambar) -->
        <div class="image-section">
            <!-- Tombol Kembali -->
            <a href="<?php echo e(route('landing-page.index')); ?>" class="back-to-landing">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
            <img src="<?php echo e(asset('assets/frontend/landing-page/assets/img/login.png')); ?>" alt="Login Illustration">
        </div>

        <!-- Bagian kanan (form login) -->
        <div class="login-section">
            <div class="logo-wrapper">
                <img src="<?php echo e(url('storage/logo_sekolah/' . $profilSekolah->foto)); ?>" alt="Logo Sekolah"
                    class="logo-img">
            </div>
            <h2>Registrasi Akun</h2>
            <p>Silakan daftar untuk membuat akun!</p>
            <form class="pt-3" method="POST" action="<?php echo e(route('register')); ?>">
                <?php echo csrf_field(); ?>

                <div class="input-group">
                    <input name="nis" value="<?php echo e(old('nis')); ?>" type="text"
                        placeholder="NIS (Nomor Induk Siswa)">
                </div>

                <div class="input-group">
                    <input name="email" value="<?php echo e(old('email')); ?>" type="email" placeholder="Email">
                </div>

                <div class="input-group">
                    <input name="username" value="<?php echo e(old('username')); ?>" type="text" placeholder="Username">
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
                    <a href="<?php echo e(route('login-siswa')); ?>">Masuk Disini</a>
                </div>
            </form>

            <footer class="login-footer">
                <p>&copy; <?php echo e(date('Y')); ?> <?php echo e($profilSekolah->nama_sekolah ?? 'Nama Sekolah'); ?>. All rights
                    reserved.</p>
            </footer>
        </div>
    </div>

    

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
            <?php if($errors->any()): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Login Gagal',
                    html: '<p style="color: #555;"><?php echo e($errors->first()); ?></p>',
                    confirmButtonText: '<i class="fas fa-redo"></i> Coba Lagi',
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'swal-btn-red'
                    }
                });
            <?php endif; ?>
        });
    </script>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/auth/register.blade.php ENDPATH**/ ?>