

<?php $__env->startSection('title', 'Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Mata Pelajaran</h2>

        <?php if($enrollments->isEmpty()): ?>
            <p class="text-center text-muted">Tidak ada mata pelajaran</p>
        <?php else: ?>
            <div class="row">
                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-12 mb-4">

                        <?php
                            $slugMapel = Str::slug($enrollment->pembelajaran->nama_mapel);
                            $slugKelas = Str::slug($enrollment->pembelajaran->kelas->nama_kelas);
                            $slugTahunAjaran = str_replace(
                                '/',
                                '-',
                                $enrollment->pembelajaran->tahunAjaran->nama_tahun,
                            );
                        ?>

                        <a href="<?php echo e(route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran])); ?>"
                            class="text-decoration-none text-dark">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect">
                                <div class="row g-3 align-items-center">
                                    <!-- Gambar (Sebelah Kiri) -->
                                    <div class="col-md-3">
                                        <img src="<?php echo e(asset('storage/covers/' . $enrollment->pembelajaran->cover)); ?>"
                                            class="img-fluid rounded" alt="Cover"
                                            style="height: 150px; object-fit: cover; width: 100%;">
                                    </div>

                                    <!-- Konten (Sebelah Kanan) -->
                                    <div class="col-md-9">
                                        <h5 class="fw-bold text-success"><?php echo e($enrollment->pembelajaran->nama_mapel); ?> -
                                            <?php echo e($enrollment->pembelajaran->kelas->nama_kelas); ?>

                                        </h5>
                                        <p>
                                            <span
                                                class="badge bg-secondary"><?php echo e($enrollment->pembelajaran->guru->name); ?></span>
                                        </p>

                                        <!-- Progress Bar -->
                                        <?php
                                            $progress = rand(50, 100);
                                        ?>
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small">
                                                <span class="fw-bold"><?php echo e($progress); ?>% Completed</span>
                                                <span
                                                    class="text-muted"><?php echo e($progress < 100 ? 'Belum Selesai' : 'Selesai'); ?></span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: <?php echo e($progress); ?>%;" aria-valuenow="<?php echo e($progress); ?>"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="profile-divider">

                                        <!-- Info -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">
                                                📚 <?php echo e($enrollment->pembelajaran->pertemuan_materi_count); ?> Materi • 👥
                                                <?php echo e($enrollment->pembelajaran->enrollments_count); ?> Siswa
                                            </span>
                                            <span class="text-success small">Klik untuk melihat detail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/mataPelajaran/index.blade.php ENDPATH**/ ?>