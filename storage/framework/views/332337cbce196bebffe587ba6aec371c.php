<script src="<?php echo e(asset('skydash/vendors/js/vendor.bundle.base.js')); ?>"></script>
<script src="<?php echo e(asset('skydash/js/off-canvas.js')); ?>"></script>
<script src="<?php echo e(asset('skydash/js/hoverable-collapse.js')); ?>"></script>
<script src="<?php echo e(asset('skydash/js/template.js')); ?>"></script>
<script src="<?php echo e(asset('skydash/js/settings.js')); ?>"></script>
<script src="<?php echo e(asset('skydash/js/todolist.js')); ?>"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.toggle-password').forEach(function(button) {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const targetInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                if (targetInput.type === 'password') {
                    targetInput.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    targetInput.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });
        });
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- SweetAlert Notification -->
<?php if($errors->any()): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'warning',
                title: '<strong>Login Gagal</strong>',
                html: '<p style="color: #555;"><?php echo e($errors->first()); ?></p>',
                confirmButtonText: '<i class="fas fa-redo"></i> Try Again',
                buttonsStyling: false,
                customClass: {
                    popup: 'custom-swal-popup', 
                    title: 'custom-swal-title', 
                    content: 'custom-swal-content', 
                    icon: 'custom-swal-icon', 
                    confirmButton: 'btn btn-danger btn-lg'
                }
            });
        });
    </script>
<?php endif; ?>

<script>
    <?php if(session('status')): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '<?php echo e(session('status')); ?>',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>

<script>
    <?php if(session('status') == 'verification-link-sent'): ?>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Tautan verifikasi baru telah dikirim ke alamat email yang Anda gunakan.',
            confirmButtonText: 'OK'
        });
    <?php endif; ?>
</script>




<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/includes/backend/js/login-script.blade.php ENDPATH**/ ?>