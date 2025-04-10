<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin">

            <div class="row">
                <div class="col-md-3 mb-3">
                    <div class="card">
                        <div class="card-body d-flex flex-column align-items-center my-3">
                            <!-- Foto Profil -->
                            <div class="mb-3">
                                <img id="previewFoto"
                                    src="<?php echo e(Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png')); ?>"
                                    alt="Foto Profil" class="rounded-circle img-fluid"
                                    style="max-width: 100px; max-height: 100px;">
                            </div>
                            <!-- Nama dan Email -->
                            <h5 class="font-weight-bold"><?php echo e(Auth::user()->name); ?></h5>
                            <label class="badge badge-pill badge-info"><?php echo e(Auth::user()->email); ?></label>
                            <a class="btn btn-outline-primary btn-sm" href="<?php echo e(route('profile.edit')); ?>">Lihat Profile</a>

                            <!-- Informasi Sekolah -->
                            <hr class="w-100">
                            <p class="font-weight-bold mb-0">SMA NEGERI 2 KERINCI KANAN</p>
                            <?php if(!empty(Auth::user()->getRoleNames())): ?>
                                <?php $__currentLoopData = Auth::user()->getRoleNames(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rolename): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <p class="text-muted"><?php echo e($rolename); ?></p>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>


                            <!-- Informasi Total Mata Pelajaran -->
                            <div class="d-flex justify-content-between align-items-center w-100">
                                <div class="d-flex align-items-center">
                                    <img src="<?php echo e(asset('assets/img/books.png')); ?>" alt="Icon"
                                        style="width: 30px; height: 30px;" class="mr-2">
                                    <h6 class="mt-2">Total Mata Pelajaran</h6>
                                </div>
                                <span class="badge badge-info badge-pill">5</span>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card 2</h5>
                            <p class="card-text">This is the content of card 2.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Card 3</h5>
                            <p class="card-text">This is the content of card 3.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/dashboard.blade.php ENDPATH**/ ?>