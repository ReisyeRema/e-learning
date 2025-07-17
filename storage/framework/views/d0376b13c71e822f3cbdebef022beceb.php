

<?php $__env->startSection('title', 'Export Rekapitulasi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('components.nav-walas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body" style="background-color: #e6eefb;">

                    <!-- Title Section -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="card-title">Export Rekapitulasi Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?>

                            (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)</p>
                    </div>

                    <!-- Filter Semester -->
                    <form method="GET" action="">
                        <div class="form-group row">
                            <label for="exampleInputUsername2" class="col-sm-2 col-form-label">Pilih Semester</label>
                            <div class="col-sm-9">
                                <select name="semester" id="semester" class="form-control" onchange="this.form.submit()">
                                    <option value="">-- Pilih Semester --</option>
                                    <option value="Ganjil" <?php echo e(request('semester') == 'Ganjil' ? 'selected' : ''); ?>>Ganjil
                                    </option>
                                    <option value="Genap" <?php echo e(request('semester') == 'Genap' ? 'selected' : ''); ?>>Genap
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>

                    <div class="list-group">
                        <a href="#"
                            class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                            <i class="fas fa-file-alt fa-2x me-4"></i>
                            <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi Nilai
                                Tugas Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?> Tahun Pelajaran
                                (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)
                                <?php if($semester): ?>
                                    - Semester <?php echo e($semester); ?>

                                <?php endif; ?>
                            </strong>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                            <i class="fas fa-file-alt fa-2x me-4"></i>
                            <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi Nilai Kuis
                                Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?> Tahun Pelajaran
                                (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)
                                <?php if($semester): ?>
                                    - Semester <?php echo e($semester); ?>

                                <?php endif; ?>
                            </strong>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                            <i class="fas fa-file-alt fa-2x me-4"></i>
                            <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Rekapitulasi Kehadiran
                                Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?> Tahun Pelajaran
                                (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)
                                <?php if($semester): ?>
                                    - Semester <?php echo e($semester); ?>

                                <?php endif; ?>
                            </strong>
                        </a>
                        <a href="#"
                            class="list-group-item list-group-item-action mb-5 py-4 px-5 rounded shadow-sm d-flex align-items-center">
                            <i class="fas fa-file-alt fa-2x me-4"></i>
                            <strong style="margin-left: 6rem; font-size: 1.3rem; font-weight: bold;">Laporan Nilai Akhir
                                Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?> Tahun Pelajaran
                                (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)
                                <?php if($semester): ?>
                                    - Semester <?php echo e($semester); ?>

                                <?php endif; ?>
                            </strong>
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/waliKelas/export.blade.php ENDPATH**/ ?>