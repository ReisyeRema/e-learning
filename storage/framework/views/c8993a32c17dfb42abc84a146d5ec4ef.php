

<?php $__env->startSection('title', 'Daftar List Tugas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h4 class="card-title mb-3">
                                Daftar List Tugas <?php echo e($pembelajaran->nama_mapel); ?> - <?php echo e($kelasData->nama_kelas); ?>

                            </h4>

                            <?php
                                $tugasTerpilih = $tugasList->firstWhere('id', request('tugas_id'));
                            ?>

                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                            <?php endif; ?>


                            <?php if($tugasTerpilih): ?>
                                <div class="mb-4" id="tugas-<?php echo e($tugasTerpilih->id); ?>">
                                    <h5><strong><?php echo e($tugasTerpilih->judul); ?></strong></h5>

                                    <div class="table-responsive">
                                        <table id="myTable" class="display expandable-table" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th style="text-align: center; width: 5%;">No</th>
                                                    <th>Nama Siswa</th>
                                                    <th>File Submit</th>
                                                    <th>LINK</th>
                                                    <th>Status</th>
                                                    <th style="text-align: center;">Nilai</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $__currentLoopData = $enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $enroll): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $key = $tugasTerpilih->id . '-' . $enroll->siswa->id;
                                                        $submission = $submitTugas->has($key)
                                                            ? $submitTugas[$key]->first()
                                                            : null;
                                                    ?>
                                                    <tr>
                                                        <td style="text-align: center;"><?php echo e($i + 1); ?></td>
                                                        <td><?php echo e($enroll->siswa->name); ?></td>
                                                        <td>
                                                            <?php if($submission && $submission->file_path): ?>
                                                                <a href="https://drive.google.com/file/d/<?php echo e($submission->file_path); ?>/view"
                                                                    target="_blank" class="btn btn-outline-primary btn-sm">
                                                                    Lihat File
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td>
                                                            <?php if($submission && $submission->url): ?>
                                                                <a href="<?php echo e($submission->url); ?>" target="_blank"
                                                                    class="btn btn-outline-info btn-sm">
                                                                    Kunjungi Link
                                                                </a>
                                                            <?php else: ?>
                                                                <span class="text-muted">-</span>
                                                            <?php endif; ?>
                                                        </td>

                                                        <td>
                                                            <?php if($submission): ?>
                                                                <?php
                                                                    $submittedAt = strtotime($submission->created_at);
                                                                    $deadline = strtotime($tugasTerpilih->pertemuanTugas->where('tugas_id', $tugasTerpilih->id)->first()?->deadline);
                                                                ?>
                                                        
                                                                <?php if($submittedAt <= $deadline): ?>
                                                                    <span class="badge badge-success">Sudah Mengumpulkan</span>
                                                                <?php else: ?>
                                                                    <span class="badge badge-warning">Terlambat</span>
                                                                <?php endif; ?>
                                                            <?php else: ?>
                                                                <span class="badge badge-danger">Belum Mengumpulkan</span>
                                                            <?php endif; ?>
                                                        </td>
                                                        
                                                        <td style="text-align: center;">
                                                            <div class="d-flex justify-content-center align-items-center">
                                                                <?php if($submission): ?>
                                                                    <form
                                                                        action="<?php echo e(route('submit-tugas.updateSkor', $submission->id)); ?>"
                                                                        method="POST" class="d-flex align-items-center">
                                                                        <?php echo csrf_field(); ?>
                                                                        <?php echo method_field('PUT'); ?>
                                                                        <input type="text" name="skor"
                                                                            value="<?php echo e($submission->skor); ?>"
                                                                            class="form-control form-control-sm mr-2"
                                                                            style="width: 60px;" min="0"
                                                                            max="100">
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-success">Simpan</button>
                                                                    </form>
                                                                <?php else: ?>
                                                                    <span class="text-muted">-</span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    Tugas tidak ditemukan atau belum dipilih.
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/tugas/list-tugas.blade.php ENDPATH**/ ?>