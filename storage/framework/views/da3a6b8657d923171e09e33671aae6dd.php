

<?php $__env->startSection('title', 'Forum'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container mt-4">
        <div class="card mb-4">
            <!-- Forum Content -->
            <div class="card-body">
                <h5 class="card-title">Forum ketiga</h5>
                <h6 class="card-subtitle mb-3 text-muted">20 minutes ago</h6>
                <p class="card-text">Bla bla Bla bla Bla bla Bla bla Bla bla Bla bla</p>
            </div>

            <!-- Komentar Section -->
            <hr class="m-0">
            <div class="d-flex justify-content-between align-items-center px-3 py-2">
                <button class="btn btn-outline-primary">
                    <i class="lnr lnr-thumbs-up me-1"></i> Suka
                </button>
                <button class="btn btn-outline-secondary" id="btn-komentar-utama">
                    <i class="lnr lnr-bubble me-1"></i> Komentar
                </button>
            </div>
            <div class="px-3 pb-3">
                <form action="" method="POST" style="display: none;" id="komentar-utama">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="forum_id" value="<?php echo e($forum->id); ?>">
                    <input type="hidden" name="parent" value="0">
                    <textarea class="form-control mt-3" name="konten" rows="4" placeholder="Tulis komentar..."></textarea>
                    <input type="submit" class="btn btn-primary" value="Kirim">
                </form>
            </div>

            <div class="card-body pt-4">
                <h5 class="mb-4">Komentar</h5>

                <?php $__currentLoopData = $forum->komentar()->where('parent', 0)->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $komentar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex align-items-start mb-3">
                        <img src="<?php echo e(asset('assets/img/profil.png')); ?>" class="rounded-circle me-3"
                            style="width: 40px; height: 40px;" alt="Profile">
                        <div>
                            <p class="mb-1">
                                <strong class="text-primary"><?php echo e($komentar->user->name); ?></strong> <?php echo e($komentar->konten); ?>

                            </p>
                            <small class="text-muted"><?php echo e($komentar->created_at->diffForHumans()); ?></small>

                            <form action="" method="POST" style="width: 100%;">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="forum_id" value="<?php echo e($forum->id); ?>">
                                <input type="hidden" name="parent" value="<?php echo e($komentar->id); ?>">
                                <input type="text" name="konten" class="form-control mb-2"
                                    placeholder="Tulis komentar..." style="width: 100%;">
                                <input type="submit" class="btn btn-primary btn-sm" value="Kirim">
                            </form>

                            <br>

                            <?php $__currentLoopData = $komentar->childs()->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <p class="mb-1">
                                    <strong class="text-primary"><?php echo e($child->user->name); ?></strong>
                                    <?php echo e($child->konten); ?>

                                </p>
                                <small class="text-muted"><?php echo e($child->created_at->diffForHumans()); ?></small>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        </div>
                    </div>
                    <hr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            $('#btn-komentar-utama').click(function() {
                $('#komentar-utama').slideToggle();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/forum/view.blade.php ENDPATH**/ ?>