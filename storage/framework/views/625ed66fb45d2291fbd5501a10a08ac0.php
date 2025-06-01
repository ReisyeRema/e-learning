

<?php $__env->startSection('content'); ?>
    <div class="text-end mb-4">
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#tambahForumModal">
            Tanya Yuk
        </button>
    </div>

    <!-- Post Card -->
    <?php $__currentLoopData = $forum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php
            $isGuru = Auth::user()->hasRole('Guru');
            $routeName = $isGuru ? 'forum-diskusi-guru.view' : 'forum-diskusi.view';
        ?>
        <div class="card p-4 mb-4 shadow border-0 rounded-4 post-card">
            <div class="d-flex justify-content-between">
                <div class="d-flex">
                    <img src="<?php echo e($frm->user->foto ? asset('storage/' . $frm->user->foto) : asset('assets/img/profil.png')); ?>"
                        class="profile-pic me-3 shadow-sm" alt="user">
                    <div>
                        <h5 class="fw-semibold mb-1"><?php echo e($frm->judul); ?></h5>
                        <small class="text-muted">
                            <?php echo e($frm->created_at != $frm->updated_at ? 'Diedit ' . $frm->updated_at->diffForHumans() : $frm->created_at->diffForHumans()); ?>

                            • Oleh <span class="text-primary fw-semibold"><?php echo e($frm->user->name); ?></span>
                        </small>
                    </div>
                </div>
                <div class="text-end d-flex flex-column justify-content-between align-items-end">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="comment-count text-secondary">
                            <i class="bi bi-chat-dots-fill me-1"></i> <?php echo e($frm->komentar ? $frm->komentar->count() : 0); ?>

                        </div>

                        <?php if(auth()->guard()->check()): ?>
                            <?php if(Auth::id() === $frm->user_id): ?>
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm p-1" type="button"
                                        id="dropdownMenuButton<?php echo e($frm->id); ?>" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="dropdownMenuButton<?php echo e($frm->id); ?>">
                                        <li>
                                            <button class="dropdown-item text-warning" data-bs-toggle="modal"
                                                data-bs-target="#editForumModal<?php echo e($frm->id); ?>">
                                                <i class="bi bi-pencil-square me-2"></i>Edit
                                            </button>
                                        </li>
                                        <li>
                                            <form id="delete-form-<?php echo e($frm->id); ?>"
                                                action="<?php echo e(route($isGuru ? 'guru.forum.destroy' : 'siswa.forum.destroy', $frm->id)); ?>"
                                                method="POST">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="button" class="dropdown-item text-danger btn-delete-forum"
                                                    data-id="<?php echo e($frm->id); ?>">
                                                    <i class="bi bi-trash3 me-2"></i>Hapus
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>

                    <a href="<?php echo e(route($routeName, [
                        'mapel' => Str::slug($pembelajaran->nama_mapel),
                        'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                        'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                        'semester' => Str::slug($pembelajaran->semester),
                        'forum' => $frm->id,
                    ])); ?>"
                        class="btn btn-sm btn-primary">Lihat</a>
                </div>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="modal fade" id="tambahForumModal" tabindex="-1" aria-labelledby="tambahForumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <?php
                $isGuru = Auth::user()->hasRole('Guru');
                $routeName = $isGuru ? 'guru.forum.store' : 'siswa.forum.store';
            ?>
            <form action="<?php echo e(route($routeName)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="tambahForumModalLabel">Tambah Forum</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" name="judul" class="form-control" value="<?php echo e(old('judul')); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="konten">Konten</label>
                            <textarea name="konten" class="form-control summernote" rows="4" required><?php echo old('konten'); ?></textarea>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="pembelajaran_id" value="<?php echo e($pembelajaran->id); ?>">
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

    <?php $__currentLoopData = $forum; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $frm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php if(auth()->guard()->check()): ?>
            <?php if(Auth::id() === $frm->user_id): ?>
                <!-- Modal Edit Forum -->
                <div class="modal fade" id="editForumModal<?php echo e($frm->id); ?>" tabindex="-1"
                    aria-labelledby="editForumModalLabel<?php echo e($frm->id); ?>" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <?php
                            $isGuru = Auth::user()->hasRole('Guru');
                            $updateRoute = $isGuru
                                ? route('guru.forum.update', $frm->id)
                                : route('siswa.forum.update', $frm->id);
                        ?>
                        <form action="<?php echo e($updateRoute); ?>" method="POST">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editForumModalLabel<?php echo e($frm->id); ?>">Edit Forum</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="judul">Judul</label>
                                        <input type="text" name="judul" class="form-control" value="<?php echo e(old('judul', $frm->judul)); ?>"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="konten">Konten</label>
                                        <textarea name="konten" class="form-control summernote" rows="4" required><?php echo old('konten', $frm->konten); ?></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-warning">Update</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(asset('skydash/summernote/summernote.min.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- include summernote css/js-->
    <script src="<?php echo e(asset('skydash/summernote/summernote.min.js')); ?>"></script>
    <script>
        $(document).ready(function() {
            // Untuk modal tambah forum
            $('#tambahForumModal').on('shown.bs.modal', function() {
                $(this).find('.summernote').not('.summernote-initialized').each(function() {
                    $(this).summernote({
                        placeholder: 'Tulis pertanyaanmu di sini...',
                        tabsize: 2,
                        height: 300
                    }).addClass('summernote-initialized');
                });
            });

            // Untuk semua modal edit forum
            $('div[id^="editForumModal"]').on('shown.bs.modal', function() {
                $(this).find('.summernote').not('.summernote-initialized').each(function() {
                    $(this).summernote({
                        placeholder: 'Edit pertanyaanmu...',
                        tabsize: 2,
                        height: 300
                    }).addClass('summernote-initialized');
                });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.forum', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/forumDiskusi/index.blade.php ENDPATH**/ ?>