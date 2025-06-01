

<?php $__env->startSection('content'); ?>
    <div class="container mt-3">
        <div class="card shadow rounded-4">
            <div class="card-body">
                <h4 class="card-title fw-bold"><?php echo e($forum->judul); ?></h4>
                <small class="text-muted">
                    <?php echo e($forum->created_at != $forum->updated_at ? 'Diedit ' . $forum->updated_at->diffForHumans() : $forum->created_at->diffForHumans()); ?>

                    • Oleh <span class="text-primary fw-semibold"><?php echo e($forum->user->name); ?></span>
                </small>
                <p class="mt-2"><?php echo $forum->konten; ?></p>
            </div>

            <div class="border-top px-4 py-3 d-flex justify-content-between align-items-center bg-light">
                
                <button class="btn btn-outline-secondary" id="btn-komentar-utama">
                    <i class="bi bi-chat-dots"></i> Komentar
                </button>
            </div>

            <div class="px-4 py-3 bg-white">
                <form action="" method="POST" id="komentar-utama" style="display: none;">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="forum_id" value="<?php echo e($forum->id); ?>">
                    <input type="hidden" name="parent" value="0">
                    <textarea name="konten" class="form-control mb-2" id="komentar-utama" rows="3" placeholder="Tulis komentar..."></textarea>
                    <button type="submit" class="btn btn-primary">Kirim</button>
                </form>
            </div>

            <div class="card-body pt-4">
                <h5 class="mb-4 fw-semibold">Komentar</h5>

                <!-- Komentar Utama -->
                <?php $__currentLoopData = $forum->komentar()->where('parent', 0)->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $komentar): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="d-flex mb-4">
                        <!-- Avatar -->
                        <img src="<?php echo e(asset('assets/img/profil.png')); ?>" class="rounded-circle me-3" width="45"
                            height="45" alt="Profile">

                        <!-- Konten komentar + form -->
                        <div class="flex-grow-1">
                            <!-- Komentar -->
                            <p class="mb-1">
                                <strong class="text-primary"><?php echo e($komentar->user->name); ?></strong> <?php echo e($komentar->konten); ?>

                            </p>
                            <small class="text-muted"><?php echo e($komentar->created_at->diffForHumans()); ?></small>

                            <!-- Form balasan -->
                            <form method="POST" class="mt-2">
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="forum_id" value="<?php echo e($forum->id); ?>">
                                <input type="hidden" name="parent" value="<?php echo e($komentar->id); ?>">

                                <!-- ROW khusus form -->
                                <div class="row g-2">
                                    <div class="col">
                                        <input type="text" name="konten" class="form-control"
                                            placeholder="Tulis komentar...">
                                    </div>
                                    <div class="col-auto">
                                        <button type="submit" class="btn btn-primary btn-sm">Kirim</button>
                                    </div>
                                </div>
                            </form>

                            <!-- Balasan -->
                            <?php $__currentLoopData = $komentar->childs()->orderBy('created_at', 'desc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="mt-3 ps-3 border-start">
                                    <p class="mb-1">
                                        <strong class="text-primary"><?php echo e($child->user->name); ?></strong>
                                        <?php echo e($child->konten); ?>

                                    </p>
                                    <small class="text-muted"><?php echo e($child->created_at->diffForHumans()); ?></small>
                                </div>
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

<?php echo $__env->make('layouts.forum', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/forumDiskusi/view.blade.php ENDPATH**/ ?>