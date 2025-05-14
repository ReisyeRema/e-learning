<h3 style="text-align: center;"><?php echo e($tugas->judul); ?></h3>
<br>
<p><strong>Mata Pelajaran:</strong> <?php echo e($pembelajaran->nama_mapel); ?></p>
<p><strong>Kelas:</strong> <?php echo e($pembelajaran->kelas->nama_kelas ?? '-'); ?></p>
<p><strong>Semester:</strong> <?php echo e($pembelajaran->semester); ?></p>

<br>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Status</th>
            <th>Skor</th>
        </tr>
    </thead>
    <tbody>
        <?php $__currentLoopData = $siswaList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $siswa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($index + 1); ?></td>
                <td><?php echo e($siswa['nama']); ?></td>
                <td><?php echo e($siswa['status']); ?></td>
                <td><?php echo e($siswa['skor']); ?></td>
            </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/tugas/table.blade.php ENDPATH**/ ?>