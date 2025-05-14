<h3 style="text-align: center;">ABSENSI</h3>
<br>
<p><strong>Mata Pelajaran:</strong> <?php echo e($pembelajaran->nama_mapel); ?></p>
<p><strong>Kelas:</strong> <?php echo e($pembelajaran->kelas->nama_kelas ?? '-'); ?></p>
<br>

<table border="1" cellspacing="0" cellpadding="5">
    <thead>
        <tr>
            <th rowspan="3">No</th>
            <th rowspan="3">Nama</th>
            <th colspan="<?php echo e($pertemuanList->count()); ?>" style="text-align: center;">Pertemuan</th>
            <th colspan="4" style="text-align: center;">Rekap</th>
        </tr>
        <tr>
            <?php $__currentLoopData = $pertemuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $pertemuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th><?php echo e($index + 1); ?></th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <th rowspan="2">Hadir</th>
            <th rowspan="2">Izin</th>
            <th rowspan="2">Sakit</th>
            <th rowspan="2">Alfa</th>
        </tr>
        <tr>
            <?php $__currentLoopData = $pertemuanList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pertemuan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th style="font-size: 4px;">
                    <?php echo e(\Carbon\Carbon::parse($pertemuan->tanggal)->format('d/m/Y')); ?>

                </th>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($siswa['nama']); ?></td>
                <?php $__currentLoopData = $siswa['pertemuan']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $keterangan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <td>
                        <?php if($keterangan === 'Hadir'): ?>
                            &#10004; 
                        <?php else: ?>
                            <?php echo e(substr($keterangan, 0, 1)); ?>

                        <?php endif; ?>
                    </td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($siswa['rekap']['Hadir']); ?></td>
                <td><?php echo e($siswa['rekap']['Izin']); ?></td>
                <td><?php echo e($siswa['rekap']['Sakit']); ?></td>
                <td><?php echo e($siswa['rekap']['Alfa']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/detailAbsensi/table.blade.php ENDPATH**/ ?>