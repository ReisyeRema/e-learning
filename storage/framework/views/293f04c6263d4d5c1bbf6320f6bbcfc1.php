

<?php $__env->startSection('title', 'Rekapitulasi Nilai Akhir'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('components.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light d-flex align-items-center">
                    <h5 class="mb-1 ml-2">Rekapitulasi Nilai Akhir</h5>
                </div>
                <div class="card-body">
                    <p class="text-muted">Gunakan tombol di bawah ini untuk mengekspor data rekapitulasi akhir dalam format
                        Excel.</p>
                    <a href="<?php echo e(route('rekap-akhir.export', ['pembelajaran_id' => $pembelajaran->id])); ?>"
                        class="btn btn-success px-5 py-3 w-100 fw-bold shadow">
                        <i class="fa fa-download me-2"></i> Export Rekapitulasi Akhir
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/rekapAkhir/show.blade.php ENDPATH**/ ?>