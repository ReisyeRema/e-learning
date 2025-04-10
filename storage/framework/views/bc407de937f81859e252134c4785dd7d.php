

<?php $__env->startSection('title', 'Daftar Tugas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Tugas</h2>

        <?php if($tugasList->isEmpty()): ?>
            <p class="text-center text-muted">Tidak ada tugas</p>
        <?php else: ?>
            <div class="row">
                <?php $__currentLoopData = $tugasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $tugas->pertemuanTugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pertemuanTugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect position-relative">

                                <?php
                                    $slugMapel = Str::slug($pertemuanTugas->pembelajaran->nama_mapel);
                                    $slugKelas = Str::slug($pertemuanTugas->pembelajaran->kelas->nama_kelas);
                                    $slugTahunAjaran = str_replace(
                                        '/',
                                        '-',
                                        $pertemuanTugas->pembelajaran->tahunAjaran->nama_tahun,
                                    );
                                ?>

                                <!-- Status di Pojok Kanan Atas -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    <?php if($tugas->submitTugas->isNotEmpty()): ?>
                                        <?php
                                            $submittedAt = strtotime($tugas->submitTugas->first()->created_at);
                                            $deadline = strtotime($pertemuanTugas->deadline);
                                        ?>
                                        <?php if($submittedAt <= $deadline): ?>
                                            <span class="badge bg-success">Terkumpul</span>
                                        <?php else: ?>
                                            <span class="badge bg-warning">Terlambat</span>
                                        <?php endif; ?>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Belum</span>
                                    <?php endif; ?>
                                </div>

                                <div class="row g-1 align-items-center">
                                    <!-- Gambar Tugas (Sebelah Kiri) -->
                                    <div class="col-md-2 text-center">
                                        <img src="<?php echo e(asset('assets/img/tugas.png')); ?>" class="img-fluid rounded"
                                            alt="Gambar tugas" style="width: 80px; height: auto; object-fit: cover;">
                                    </div>


                                    <!-- Konten Tugas (Sebelah Kanan) -->
                                    <div class="col-md-10">
                                        <h5 class="fw-bold text-success mb-2"><?php echo e($tugas->judul); ?></h5>
                                        <span class="badge bg-secondary mb-2">
                                            <?php echo e($pertemuanTugas->pembelajaran->nama_mapel); ?> -
                                            <?php echo e($pertemuanTugas->pembelajaran->kelas->nama_kelas); ?>

                                        </span>

                                        <p class="mb-2"><strong>Tenggat:</strong>
                                            <?php echo e(date('d F Y - H:i', strtotime($pertemuanTugas->deadline))); ?>

                                        </p>

                                        <?php if($tugas->submitTugas->isNotEmpty()): ?>
                                            <p class="mb-1"><strong>Dikumpulkan:</strong>
                                                <?php echo e(date('d F Y - H:i', strtotime($tugas->submitTugas->first()->created_at))); ?>

                                            </p>
                                        <?php else: ?>
                                            <p class="mb-1"><strong>Dikumpulkan:</strong> -</p>
                                        <?php endif; ?>

                                        <div class="text-end mt-2">
                                            <?php if($tugas->submitTugas->isNotEmpty()): ?>
                                                <a href="#" class="text-decoration-none fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailTugas<?php echo e($tugas->id); ?>">Lihat</a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran])); ?>"
                                                    class="text-decoration-none fw-bold">Kumpulkan</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($tugas->submitTugas->isNotEmpty()): ?>
                            <div class="modal fade" id="modalDetailTugas<?php echo e($tugas->id); ?>" tabindex="-1"
                                aria-labelledby="modalDetailTugasLabel<?php echo e($tugas->id); ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetailTugasLabel<?php echo e($tugas->id); ?>">Detail
                                                Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Judul:</strong> <?php echo e($tugas->judul); ?></p>
                                            <p><strong>Dikumpulkan pada:</strong>
                                                <?php echo e(date('d F Y - H:i', strtotime($tugas->submitTugas->first()->created_at))); ?>

                                            </p>
                                            <p><strong>File Tugas:</strong>
                                                <?php if($tugas->submitTugas->first()->url): ?>
                                                    <a href="<?php echo e($tugas->submitTugas->first()->url); ?>"
                                                        target="_blank">Klik di sini untuk melihat tugas yang dikumpulkan</a>
                                                <?php elseif($tugas->submitTugas->first()->file_path): ?>
                                                    <a href="https://drive.google.com/file/d/<?php echo e($tugas->submitTugas->first()->file_path); ?>/view"
                                                        target="_blank" class="btn btn-primary">Lihat Tugas</a>
                                                <?php else: ?>
                                                    <span class="text-muted">Tidak ada file tersedia</span>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    title: "Berhasil!",
                    text: "<?php echo e(session('success')); ?>",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            <?php endif; ?>
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/tugas/index.blade.php ENDPATH**/ ?>