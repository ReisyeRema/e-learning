

<?php $__env->startSection('title', 'Daftar Tugas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Tugas</h2>

        <?php if($pertemuanTugasAktif->isEmpty() && $pertemuanTugasDraft->isEmpty()): ?>
            <p class="text-center text-muted">Tidak ada tugas</p>
        <?php endif; ?>

        
        <?php if($pertemuanTugasAktif->isNotEmpty()): ?>
            <div class="row">
                <?php $__currentLoopData = $pertemuanTugasAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pertemuanTugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('components.frontend.tugas-card', [
                        'pertemuanTugas' => $pertemuanTugas,
                        'isDraft' => false,
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>

        
        <?php if($pertemuanTugasDraft->isNotEmpty()): ?>
            <hr class="my-5">
            <h4 class="text-center text-muted mb-3">Tugas dari Mata Pelajaran Tidak Aktif</h4>
            <div class="row">
                <?php $__currentLoopData = $pertemuanTugasDraft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pertemuanTugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('components.frontend.tugas-card', [
                        'pertemuanTugas' => $pertemuanTugas,
                        'isDraft' => true,
                    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
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