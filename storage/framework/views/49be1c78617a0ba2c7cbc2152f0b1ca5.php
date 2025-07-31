

<?php $__env->startSection('title', 'Data Siswa'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="card-title">Penilaian Kuis: <?php echo e($kuis->judul); ?> - <?php echo e($hasil->siswa->name); ?></p>
                            </div>

                            <!-- Table Section -->
                            <form
                                action="<?php echo e(route('hasil-kuis.updateEssay', ['kuis' => $kuis->id, 'siswa' => $hasil->siswa_id])); ?>"
                                method="POST">
                                <?php echo csrf_field(); ?>
                                <div class="table-responsive">
                                    <table id="myTable" class="display expandable-table" style="width:100%">

                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Soal</th>
                                                <th>Jawaban Siswa</th>
                                                <th>Jawaban Benar</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $jawabanUser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $soalId => $jawaban): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $soal = $soalList[$soalId] ?? null;
                                                ?>

                                                <?php if($soal): ?>
                                                    <tr>
                                                        <td><?php echo e($loop->iteration); ?></td>
                                                        <td><?php echo $soal->teks_soal; ?></td>
                                                        <td><?php echo $jawaban['jawaban'] ?? '-'; ?></td>
                                                        <td><?php echo e($soal->jawaban_benar ?? '-'); ?></td>
                                                        <td>
                                                            <?php if($soal->type_soal === 'Essay'): ?>
                                                                <?php
                                                                    // Tentukan nilai default jika belum pernah dinilai
                                                                    $defaultBenar =
                                                                        $jawaban['is_benar'] ??
                                                                        trim(strip_tags($jawaban['jawaban'] ?? '')) ===
                                                                            trim(
                                                                                strip_tags($soal->jawaban_benar ?? ''),
                                                                            );
                                                                ?>
                                                                <select name="jawaban_benar[<?php echo e($soalId); ?>]"
                                                                    class="form-control">
                                                                    <option value="1"
                                                                        <?php echo e($defaultBenar ? 'selected' : ''); ?>>Benar</option>
                                                                    <option value="0"
                                                                        <?php echo e(!$defaultBenar ? 'selected' : ''); ?>>Salah
                                                                    </option>
                                                                </select>
                                                            <?php else: ?>
                                                                <?php
                                                                    $status =
                                                                        strtolower(
                                                                            trim((string) ($jawaban['jawaban'] ?? '')),
                                                                        ) ===
                                                                        strtolower(
                                                                            trim((string) ($soal->jawaban_benar ?? '')),
                                                                        )
                                                                            ? 'Benar'
                                                                            : 'Salah';

                                                                    $badgeClass =
                                                                        $status === 'Benar'
                                                                            ? 'badge-success'
                                                                            : 'badge-danger';
                                                                ?>
                                                                <span
                                                                    class="badge <?php echo e($badgeClass); ?>"><?php echo e($status); ?></span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endif; ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                    <button type="submit" class="btn btn-primary mt-3">Simpan Penilaian</button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/kuis/hasil-kuis.blade.php ENDPATH**/ ?>