

<?php $__env->startSection('title', 'Mata Pelajaran'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Mata Pelajaran</h2>

        <div class="d-flex justify-content-center mb-2">
        <form method="GET" action="<?php echo e(route('mata-pelajaran.index')); ?>" class="mb-4">
            <div class="row g-2">
                <div class="col-md-3">
                    <input type="text" name="mapel" value="<?php echo e(request('mapel')); ?>" class="form-control" placeholder="Nama Mapel">
                </div>
        
                <div class="col-md-3">
                    <select name="kelas" class="form-select">
                        <option value="">-- Semua Kelas --</option>
                        <?php $__currentLoopData = $kelasList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kelas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($kelas->id); ?>" <?php echo e(request('kelas') == $kelas->id ? 'selected' : ''); ?>>
                                <?php echo e($kelas->nama_kelas); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
        
                <div class="col-md-3">
                    <select name="tahun_ajaran" class="form-select">
                        <option value="">-- Semua Tahun Ajaran --</option>
                        <?php $__currentLoopData = $tahunAjaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($tahun->id); ?>" <?php echo e(request('tahun_ajaran') == $tahun->id ? 'selected' : ''); ?>>
                                <?php echo e($tahun->nama_tahun); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
        
                <div class="col-md-3 mt-2">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="<?php echo e(route('mata-pelajaran.index')); ?>" class="btn btn-secondary">Reset</a>
                </div>
            </div>
        </form>
        </div>
        
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
                            $slugSemester = Str::slug($enrollment->pembelajaran->semester);
                        ?>

                        <a href="<?php echo e(route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran, 'semester' => $slugSemester])); ?>"
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
                                            <?php echo e($enrollment->pembelajaran->kelas->nama_kelas); ?> -
                                            <?php echo e($enrollment->pembelajaran->semester); ?>

                                        </h5>
                                        <p>
                                            <span
                                                class="badge bg-secondary"><?php echo e($enrollment->pembelajaran->guru->name); ?></span>
                                        </p>


                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small">
                                                <span class="fw-bold"><?php echo e($enrollment->progress); ?>% Completed</span>
                                                <span class="text-muted">
                                                    <?php echo e($enrollment->progress < 100 ? 'Belum Selesai' : 'Selesai'); ?>

                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: <?php echo e($enrollment->progress); ?>%;"
                                                    aria-valuenow="<?php echo e($enrollment->progress); ?>" aria-valuemin="0"
                                                    aria-valuemax="100">
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

                <?php if($draftEnrollments->isNotEmpty()): ?>
                    <hr class="my-5">
                    <h4 class="text-center text-muted">📄 Draft / Belum Aktif</h4>
                    <div class="row">
                        <?php $__currentLoopData = $draftEnrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $slugMapel = Str::slug($enrollment->pembelajaran->nama_mapel);
                                $slugKelas = Str::slug($enrollment->pembelajaran->kelas->nama_kelas);
                                $slugTahunAjaran = str_replace(
                                    '/',
                                    '-',
                                    $enrollment->pembelajaran->tahunAjaran->nama_tahun,
                                );
                                $slugSemester = Str::slug($enrollment->pembelajaran->semester);
                            ?>

                            <div class="col-12 mb-4">
                                <a href="<?php echo e(route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran, 'semester' => $slugSemester])); ?>"
                                    class="text-decoration-none text-muted">
                                    <div class="card shadow-sm border-20 rounded p-3 bg-light" style="opacity: 0.6;">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-md-3">
                                                <img src="<?php echo e(asset('storage/covers/' . $enrollment->pembelajaran->cover)); ?>"
                                                    class="img-fluid rounded"
                                                    style="height: 150px; object-fit: cover; width: 100%;" alt="Cover">
                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="fw-bold"><?php echo e($enrollment->pembelajaran->nama_mapel); ?> -
                                                    <?php echo e($enrollment->pembelajaran->kelas->nama_kelas); ?> -
                                                    <?php echo e($enrollment->pembelajaran->semester); ?></h5>
                                                <p class="mb-1">
                                                    <span
                                                        class="badge bg-secondary"><?php echo e($enrollment->pembelajaran->guru->name); ?></span>
                                                </p>
                                                <small class="fst-italic text-danger">Mata pelajaran ini tidak aktif
                                                    (draft)</small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/mataPelajaran/index.blade.php ENDPATH**/ ?>