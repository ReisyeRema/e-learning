<?php $__env->startSection('title', 'Verifikasi Email'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <?php echo e(__('Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi alamat email Anda dengan mengeklik tautan yang baru saja kami kirimkan kepada Anda? Jika Anda tidak menerima email tersebut, kami akan dengan senang hati mengirimkan email lain kepada Anda.')); ?>

    </div>

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="<?php echo e(route('verification.send')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mt-3">
                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                    Resend Verification Email
                </button>
            </div>
        </form>

        <form method="POST" action="<?php echo e(route('logout')); ?>">
            <?php echo csrf_field(); ?>

            <div class="form-group d-flex justify-content-end">
                <button type="submit" class="btn btn-light mt-2">Logout</button>
            </div>
        </form>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/auth/verify-email.blade.php ENDPATH**/ ?>