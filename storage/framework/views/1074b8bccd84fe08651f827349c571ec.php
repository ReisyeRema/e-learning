

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo $__env->make('components.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Content -->
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h4 class="mb-3">Daftar Materi Siswa <?php echo e($kelas->nama_kelas); ?></h4>

                    <div class="row mt-4">
                        <!-- Sidebar Pertemuan -->
                        <div class="col-md-4">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    Tambahkan
                                </div>
                                <ul class="list-group list-group-flush">
                                    
                                </ul>
                            </div>
                        </div>

                        <!-- List Submateri -->
                        <div class="col-md-8">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    
                                </div>
                                <div class="card-body">
                                    
                                </div>
                            </div>
                        </div>

                    </div> <!-- End Row -->
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/guru/materi/index.blade.php ENDPATH**/ ?>