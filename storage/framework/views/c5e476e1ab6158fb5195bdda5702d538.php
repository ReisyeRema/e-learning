

<?php $__env->startSection('title', 'Daftar Siswa'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo $__env->make('components.nav-walas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">

                            <!-- Title Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title">Daftar Mata Pelajaran - <?php echo e($waliKelas->kelas->nama_kelas); ?>

                                    (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)</p>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-3">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th style="text-align: center">Nama</th>
                                            <th style="text-align: center">Guru</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $mapelList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr style="text-align: center">
                                                <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                                <td><?php echo e($item->nama_mapel); ?></td>
                                                <td><?php echo e($item->guru->name ?? '-'); ?></td>
                                               
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/waliKelas/list-mapel-kelas.blade.php ENDPATH**/ ?>