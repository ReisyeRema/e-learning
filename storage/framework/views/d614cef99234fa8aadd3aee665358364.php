

<?php $__env->startSection('title', 'Daftar List Kuis'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-3">
                                Daftar List Kuis <?php echo e($pembelajaran->nama_mapel); ?> - <?php echo e($kelasData->nama_kelas); ?>

                            </h4>

                            <?php
                                $kuisTerpilih = $kuisList->firstWhere('id', request('kuis_id'));
                            ?>

                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>


                            <?php if($kuisTerpilih): ?>
                                <div class="mb-4" id="tugas-<?php echo e($kuisTerpilih->id); ?>">
                                    <h5><strong><?php echo e($kuisTerpilih->judul); ?></strong></h5>

                                    <div class="table-responsive">
                                        <table id="myTable" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 5%;">No</th>
                                                    <th style="text-align: center;">Nama Siswa</th>
                                                    <th style="text-align: center;">Status</th>
                                                    <th style="text-align: center;">Nilai</th>
                                                    <th style="text-align: center;">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $enroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $key = $kuisTerpilih->id . '-' . $enroll->siswa->id;
                                                        $submission = $hasilKuis->has($key)
                                                            ? $hasilKuis[$key]->first()
                                                            : null;
                                                    ?>
                                                    <tr style="text-align: center">
                                                        <td style="text-align: center;"><?php echo e($i + 1); ?></td>
                                                        <td><?php echo e($enroll->siswa->name); ?></td>

                                                        <td>
                                                            <?php if($submission): ?>
                                                                <?php
                                                                    $submittedAt = strtotime($submission->created_at);
                                                                    $deadline = strtotime(
                                                                        $kuisTerpilih->pertemuanKuis
                                                                            ->where('kuis_id', $kuisTerpilih->id)
                                                                            ->first()?->deadline,
                                                                    );
                                                                ?>

                                                                <?php if($submittedAt <= $deadline): ?>
                                                                    <span class="badge badge-success">Sudah
                                                                        Mengerjakan</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-warning">Terlambat</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger">Belum Mengerjakan</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td style="text-align: center;">
                                                            <?php if($submission && $submission->skor_total): ?>
                                                                <strong><?php echo e($submission->skor_total); ?></strong>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="text-center">
                                                            <a href="<?php echo e(route('hasil-kuis.show', [
                                                                'mapel' => Str::slug($pembelajaran->nama_mapel, '-'),
                                                                'kelas' => Str::slug($kelasData->nama_kelas, '-'),
                                                                'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                                                                'semester' => Str::slug($pembelajaran->semester, '-'),
                                                                'kuis' => $kuisTerpilih->id,
                                                                'siswa' => $enroll->siswa->id,
                                                            ])); ?>"
                                                                class="btn btn-sm btn-outline-primary btn-fw">
                                                                Lihat Jawaban
                                                            </a>
                                                        </td>

                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    Kuis tidak ditemukan atau belum dipilih.
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/kuis/list-kuis.blade.php ENDPATH**/ ?>