<?php $__env->startSection('title', 'Lupa Password'); ?>

<?php $__env->startSection('content'); ?>

    <?php if(session('status')): ?>
        <div class="alert alert-success">
            <?php echo e(session('status')); ?>

        </div>
    <?php endif; ?>

    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <?php echo e(__('Lupa kata sandi? Tidak masalah. Cukup beri tahu kami alamat email Anda dan kami akan mengirimkan tautan untuk menyetel ulang kata sandi yang akan memungkinkan Anda memilih kata sandi baru.')); ?>

    </div>

    <form class="pt-3" method="POST" action="<?php echo e(route('password.email')); ?>">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input name="email" :value="old('email')" required autofocus autocomplete="email" id="email" type="email"
                class="form-control form-control-lg" placeholder="Masukkan email Anda">
        </div>

        <div class="mt-3">
            <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">
                Tautan Reset Kata Sandi Email
            </button>
        </div>

    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>