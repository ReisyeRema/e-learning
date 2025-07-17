

<?php $__env->startSection('title', 'Pilih Role'); ?>

<?php $__env->startSection('content'); ?>
    <h4 class="text-center">Pilih Role Anda</h4>
    <form method="POST" action="<?php echo e(route('choose-role.submit')); ?>">
        <?php echo csrf_field(); ?>
        <div class="form-group">
            <label for="role">Silakan pilih role untuk sesi ini:</label>
            <select name="role" id="role" class="form-control" required>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->name); ?>"><?php echo e($role->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <button class="btn btn-primary mt-3 btn-block">Lanjutkan</button>
    </form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/auth/choose-role.blade.php ENDPATH**/ ?>