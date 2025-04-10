

<?php $__env->startSection('title', 'Data User'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Operator</p>
                                <!-- Button to trigger modal -->
                                <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">Tambah Data</a>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">

                                <a href="<?php echo e(route('operator.export')); ?>" class="btn btn-sm btn-inverse-success btn-icon-text">
                                    <i class="ti-download btn-icon-prepend"></i>                                                    
                                    Download XMLS
                                </a>

                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                                <td> <?php echo e($item->name); ?> </td>
                                                <td> <?php echo e($item->username); ?> </td>
                                                <td> <?php echo e($item->email); ?> </td>
                                                <td>
                                                    <?php $__currentLoopData = $item->roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <span class="badge badge-info"><?php echo e($role->name); ?></span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="<?php echo e(route('users.edit', $item->id)); ?>" class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm<?php echo e($item->id); ?>" action="<?php echo e(route('users.destroy', $item->id)); ?>" method="POST" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-fw" onclick="confirmDelete('<?php echo e($item->id); ?>')">Delete</button>
                                                        </form>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/superadmin/user/index.blade.php ENDPATH**/ ?>