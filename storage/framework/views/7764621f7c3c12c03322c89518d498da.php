<style>
    .thead-green th {
        background-color: #198754;
        color: white;
        border-color: white;
    }
</style>

<div>
    <h6 class="fw-bold">Absensi Siswa</h6>

    <!-- Tombol Absensi -->
    <div class="mb-3">
        <?php if($absensiAktif && $waktuAbsensiBelumDimulai): ?>
            <button class="btn btn-warning" disabled>
                Waktu Absensi Belum Dimulai
            </button>
        <?php elseif($absensiAktif && $absensiMasihAktif): ?>
            <?php if($detailAbsensi): ?>
                <button class="btn btn-light" disabled>
                    Sudah Absen Hari Ini (<?php echo e($detailAbsensi->keterangan); ?>)
                </button>
            <?php else: ?>
                <!-- Tombol untuk memicu modal -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAbsensi">
                    Lakukan Absensi
                </button>
            <?php endif; ?>
        <?php elseif($absensiAktif && !$absensiMasihAktif): ?>
            <button class="btn btn-secondary" disabled>Waktu Absensi Telah Berakhir</button>
        <?php else: ?>
            <button class="btn btn-light" disabled>Belum Tersedia</button>
        <?php endif; ?>
    </div>



    <!-- Riwayat Absensi -->
    <h6 class="fw-bold mt-4">Riwayat Absensi</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center shadow-sm rounded" style="border-color: #198754;">
            <thead class="thead-green">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam Absen</th>
                    <th>Status</th>
                    <th>Dokumen</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $riwayatAbsensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $absen): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $tanggal = $absen->absensi->tanggal;
                        $jamAbsen = \Carbon\Carbon::parse($absen->created_at)->format('H:i'); // atau 'H:i:s' untuk detik
                    ?>
                    <tr style="background-color: <?php echo e($absen->keterangan == 'Hadir' ? '#e9f7ef' : '#fff3f3'); ?>;">
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e(\Carbon\Carbon::parse($tanggal)->translatedFormat('d M Y')); ?></td>
                        <td><?php echo e($jamAbsen); ?></td>
                        <td>
                            <?php
                                switch ($absen->keterangan) {
                                    case 'Hadir':
                                        $badgeClass = 'bg-success'; // Hijau
                                        break;
                                    case 'Izin':
                                        $badgeClass = 'bg-warning text-dark'; // Kuning
                                        break;
                                    case 'Sakit':
                                        $badgeClass = 'bg-info text-dark'; // Biru Muda
                                        break;
                                    case 'Alfa':
                                        $badgeClass = 'bg-danger'; // Merah
                                        break;
                                    default:
                                        $badgeClass = 'bg-secondary';
                                }
                            ?>
                            <span class="badge <?php echo e($badgeClass); ?>">
                                <?php echo e($absen->keterangan); ?>

                            </span>
                        </td>

                        <td>
                            <?php if(in_array($absen->keterangan, ['Izin', 'Sakit']) && $absen->surat): ?>
                                <a href="https://drive.google.com/file/d/<?php echo e($absen->surat); ?>/view" target="_self"
                                    class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-file-alt me-1"></i> Lihat Surat
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">Belum ada riwayat absensi.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/mataPelajaran/absensi.blade.php ENDPATH**/ ?>