

<?php $__env->startSection('title', 'Forum'); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-white shadow-md rounded-lg">

        
        <div class="flex justify-end mb-4">
            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahForumModal">
                Tambah Forum
            </button>
        </div>

        
        <div class="space-y-4">
            <?php $__currentLoopData = $forum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center space-x-4 border-b py-3">
                    <img src="<?php echo e($frm->user->foto ? asset('storage/' . $frm->user->foto) : asset('assets/img/profil.png')); ?>"
                        class="rounded-full object-cover" style="width: 50px; height: 50px;">
                    <div>
                        <a href="<?php echo e(route('forum.view', $frm->id)); ?>" class="text-blue-600 font-medium">
                            <?php echo e($frm->user->name); ?> : <?php echo e($frm->judul); ?>

                        </a>
                        <div class="text-sm text-gray-500"><?php echo e($frm->created_at->diffForHumans()); ?></div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>

    
    <div class="modal fade" id="tambahForumModal" tabindex="-1" aria-labelledby="tambahForumModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="<?php echo e(route('forum.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahForumModalLabel">Tambah Forum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" name="judul" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="konten">Konten</label>
                            <textarea name="konten" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="pembelajaran_id">Pembelajaran</label>
                            <select name="pembelajaran_id" class="form-control" required>
                                <?php $__currentLoopData = $pembelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($pb->id); ?>"><?php echo e($pb->nama_mapel); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kirim</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/forum/index.blade.php ENDPATH**/ ?>