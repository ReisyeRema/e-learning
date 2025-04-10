

<?php $__env->startSection('title', 'Data Role'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body p-5">
                    <div class="row">

                        <!-- Role Name -->
                        <div class="mb-2">
                            <h1 class="card-title text-primary">
                                <i class="fa fa-th-list"></i> Role: <?php echo e($role->name); ?>

                            </h1>
                        </div>

                        <!-- Form to Add Permissions -->
                        <form action="<?php echo e(route('roles.addPermissionToRole', $role->id)); ?>" method="POST" class="w-100">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <!-- Permissions Section -->
                            <div class="form-group">
                                <label for="permissions">Permissions</label>
                                <div class="row">
                                    <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="col-md-3">
                                            <div class="form-check form-check-flat">
                                                <label class="form-check-label">
                                                    <input class="checkbox" type="checkbox" name="permission[]" 
                                                    value="<?php echo e($item->name); ?>" 
                                                            <?php echo e(in_array($item->id, $rolePermissions) ? 'checked' : ''); ?>>
                                                    <?php echo e($item->name); ?>

                                                </label>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="tile-footer mt-4">
                                <button class="btn btn-primary" type="submit">Update</button>
                                <a href="<?php echo e(route('roles.index')); ?>" class="btn btn-light">Cancel</a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/superadmin/role/add-permissions.blade.php ENDPATH**/ ?>