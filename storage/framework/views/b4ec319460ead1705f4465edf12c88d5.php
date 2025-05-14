

<?php $__env->startSection('title', 'Detail Absensi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo $__env->make('components.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Absensi Siswa <?php echo e($absensi->pembelajaran->kelas->nama_kelas); ?> -
                            <?php echo e($absensi->pertemuan->judul); ?></h4>
                        <!-- Button to trigger modal -->
                    </div>
                    <form method="POST" action="<?php echo e(route('detail-absensi.storeOrUpdate')); ?>">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="absensi_id" value="<?php echo e($absensi->id); ?>">

                        <div class="table-responsive">
                            <table id="myTable" class="display expandable-table" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="text-align: center" width="3%">No</th>
                                        <th>Siswa</th>
                                        <th width="3%">Hadir</th>
                                        <th width="3%">Izin</th>
                                        <th width="3%">Sakit</th>
                                        <th width="3%">Alfa</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $enrolledSiswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $enroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $siswa = $enroll->siswa;
                                            $siswaId = $siswa->id;
                                            $detail = $detailAbsensi[$siswaId] ?? null;
                                        ?>
                                        <tr>
                                            <td style="text-align: center"><?php echo e($index + 1); ?></td>
                                            <td><?php echo e($siswa->name); ?></td>
                                            <?php $__currentLoopData = ['H' => 'Hadir', 'I' => 'Izin', 'S' => 'Sakit', 'A' => 'Alfa']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input absensi-radio" type="radio"
                                                            name="keterangan[<?php echo e($siswaId); ?>]"
                                                            value="<?php echo e($key); ?>" data-siswa-id="<?php echo e($siswaId); ?>"
                                                            data-absensi-id="<?php echo e($absensi->id); ?>"
                                                            <?php echo e($detail && $detail->keterangan[0] === $key ? 'checked' : ''); ?>>
                                                    </div>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>

                        
                    </form>

                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.querySelectorAll('.absensi-radio').forEach(radio => {
            radio.addEventListener('change', function() {
                const siswaId = this.dataset.siswaId;
                const absensiId = this.dataset.absensiId;
                const keterangan = this.value;

                fetch("<?php echo e(route('detail-absensi.storeOrUpdate')); ?>", {
                        method: "POST",
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        },
                        body: JSON.stringify({
                            absensi_id: absensiId,
                            keterangan: {
                                [siswaId]: keterangan
                            }
                        })
                    })
                    .then(response => {
                        if (!response.ok) {
                            console.error('Gagal menyimpan');
                        }
                    })
                    .catch(error => {
                        console.error('Terjadi kesalahan saat menyimpan:', error);
                    });
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/detailAbsensi/index.blade.php ENDPATH**/ ?>