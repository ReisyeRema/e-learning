

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
                                <p class="card-title">Daftar Siswa - <?php echo e($waliKelas->kelas->nama_kelas); ?>

                                    (<?php echo e($waliKelas->tahunAjaran->nama_tahun); ?>)</p>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive mt-3">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th style="text-align: center">Nama</th>
                                            <th style="text-align: center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr style="text-align: center">
                                                <td> <?php echo e($loop->iteration); ?> </td>
                                                <td> <?php echo e($item->user->name); ?> </td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center">
                                                        <button type="button" class="btn btn-sm btn-outline-success"
                                                            data-toggle="modal"
                                                            data-target="#DetailModal<?php echo e($item->id); ?>">
                                                            Lihat
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal Detail Siswa -->
                                            <div class="modal fade" id="DetailModal<?php echo e($item->id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="DetailModalLabel<?php echo e($item->id); ?>"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content border-0 shadow-lg rounded-3">
                                                        <div class="modal-header bg-primary text-white">
                                                            <h5 class="modal-title"
                                                                id="DetailModalLabel<?php echo e($item->id); ?>">
                                                                Detail Siswa
                                                            </h5>
                                                            <button type="button" class="close text-white"
                                                                data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Nama Lengkap:</strong><br>
                                                                    <?php echo e($item->user->name); ?>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>NIS:</strong><br>
                                                                    <?php echo e($item->nis); ?>

                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Tempat Lahir:</strong><br>
                                                                    <?php echo e($item->tempat_lahir); ?>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Tanggal Lahir:</strong><br>
                                                                    <?php echo e($item->tanggal_lahir->format('d-m-Y')); ?>

                                                                </div>
                                                            </div>
                                                            <div class="row mb-3">
                                                                <div class="col-md-6">
                                                                    <strong>Jenis Kelamin:</strong><br>
                                                                    <?php echo e(ucfirst($item->jenis_kelamin)); ?>

                                                                </div>
                                                                <div class="col-md-6">
                                                                    <strong>Alamat:</strong><br>
                                                                    <?php echo e($item->alamat); ?>

                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Tutup</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- End Modal -->
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

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/waliKelas/list-siswa-kelas.blade.php ENDPATH**/ ?>