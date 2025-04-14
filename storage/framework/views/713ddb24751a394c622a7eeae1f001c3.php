

<?php $__env->startSection('title', 'Daftar Kuis dan Ujian'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Kuis dan Ujian</h2>

        <?php if($kuisList->isEmpty()): ?>
            <p class="text-center text-muted">Tidak ada tugas</p>
        <?php else: ?>
            <div class="row">
                <?php $__currentLoopData = $kuisList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $__currentLoopData = $kuis->pertemuanKuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pertemuanKuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect position-relative">

                                <?php
                                    $slugMapel = Str::slug($pertemuanKuis->pembelajaran->nama_mapel);
                                    $slugKelas = Str::slug($pertemuanKuis->pembelajaran->kelas->nama_kelas);
                                    $slugTahunAjaran = str_replace(
                                        '/',
                                        '-',
                                        $pertemuanKuis->pembelajaran->tahunAjaran->nama_tahun,
                                    );
                                ?>

                                <!-- Status di Pojok Kanan Atas -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    <?php if($kuis->hasilKuis->isNotEmpty()): ?>
                                        <?php
                                            $submittedAt = strtotime($kuis->hasilKuis->first()->created_at);
                                            $deadline = strtotime($pertemuanKuis->deadline);
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
                                        <h5 class="fw-bold text-success mb-2"><?php echo e($kuis->judul); ?></h5>
                                        <span class="badge bg-secondary mb-2">
                                            <?php echo e($pertemuanKuis->pembelajaran->nama_mapel); ?> -
                                            <?php echo e($pertemuanKuis->pembelajaran->kelas->nama_kelas); ?>

                                        </span>

                                        <p class="mb-2"><strong>Tenggat:</strong>
                                            <?php echo e(date('d F Y - H:i', strtotime($pertemuanKuis->deadline))); ?>

                                        </p>

                                        <?php if($kuis->hasilKuis->isNotEmpty()): ?>
                                            <p class="mb-1"><strong>Selesai:</strong>
                                                <?php echo e(date('d F Y - H:i', strtotime($kuis->hasilKuis->first()->created_at))); ?>

                                            </p>
                                        <?php else: ?>
                                            <p class="mb-1"><strong>Selesai:</strong> -</p>
                                        <?php endif; ?>

                                        <div class="text-end mt-2">
                                            <?php if($kuis->hasilKuis->isNotEmpty()): ?>
                                                <a href="#" class="text-decoration-none fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailTugas<?php echo e($kuis->id); ?>">Lihat</a>
                                            <?php else: ?>
                                                <a href="<?php echo e(route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran])); ?>"
                                                    class="text-decoration-none fw-bold">Kerjakan</a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if($kuis->hasilKuis->isNotEmpty()): ?>
                            <div class="modal fade" id="modalDetailTugas<?php echo e($kuis->id); ?>" tabindex="-1"
                                aria-labelledby="modalDetailTugasLabel<?php echo e($kuis->id); ?>" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetailTugasLabel<?php echo e($kuis->id); ?>">Detail
                                                Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Judul</strong></div>
                                                <div>: <?php echo e($kuis->judul); ?></div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Selesai pada</strong></div>
                                                <div>: <?php echo e(date('d F Y - H:i', strtotime($kuis->hasilKuis->first()->created_at))); ?></div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Nilai</strong></div>
                                                <div>: 
                                                    <span class="badge bg-success fs-8">
                                                        <?php echo e($kuis->hasilKuis->first()->skor_total); ?>

                                                    </span>
                                                </div>
                                            </div>
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

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/kuis/index.blade.php ENDPATH**/ ?>