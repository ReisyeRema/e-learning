

<?php $__env->startSection('title', 'Data Tugas'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Header Section -->
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4>Daftar Tugas - <?php echo e($kuis->judul); ?></h4>
                                <button type="button" class="btn btn-sm btn-dark" data-toggle="modal"
                                    data-target="#addKelasModal">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>

                            <!-- List Soal -->
                            <div class="list-group">
                                <?php $__currentLoopData = $soalKuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div
                                        class="list-group-item d-flex align-items-center justify-content-between p-3 shadow-sm rounded mb-2">
                                        <!-- Info Soal -->
                                        <div class="d-flex align-items-center">
                                            <!-- Nomor -->
                                            <div class="d-flex justify-content-center align-items-center rounded bg-primary text-white font-weight-bold"
                                                style="width: 40px; height: 40px; font-size: 18px;">
                                                <?php echo e($loop->iteration); ?>

                                            </div>
                                            <!-- Detail -->
                                            <div class="ml-3">
                                                <p class="text-muted small mb-1"><?php echo e(Str::limit($item->teks_soal, 50)); ?></p>
                                                <span class="badge badge-light"><?php echo e($item->type_soal); ?></span>
                                            </div>
                                        </div>

                                        <!-- Aksi -->
                                        <div class="d-flex">
                                            <button class="btn btn-light btn-sm mr-2" data-toggle="modal"
                                                data-target="#editKelasModal<?php echo e($item->id); ?>">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-light btn-sm" data-toggle="modal"
                                                data-target="#deleteKelasModal<?php echo e($item->id); ?>"
                                                onclick="confirmDelete(<?php echo e($item->id); ?>)">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <form id="deleteForm<?php echo e($item->id); ?>"
                                                action="<?php echo e(route('soal.destroy', $item->id)); ?>" method="POST"
                                                style="display: none;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Modal Edit -->
                                    <!-- Modal Edit -->
                                    <div class="modal fade" id="editKelasModal<?php echo e($item->id); ?>" tabindex="-1"
                                        aria-labelledby="editKelasModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Soal</h5>
                                                    <button type="button" class="close" data-dismiss="modal">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <form action="<?php echo e(route('soal.update', $item->id)); ?>" method="POST"
                                                    enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>

                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label for="teks_soal">Teks Soal</label>
                                                            <textarea class="form-control" name="teks_soal" required><?php echo e($item->teks_soal); ?></textarea>
                                                        </div>

                                                        <input type="hidden" name="kuis_id" value="<?php echo e($kuis->id); ?>">

                                                        <div class="form-group">
                                                            <label for="gambar">Unggah Gambar (Opsional)</label>
                                                            <input type="file" class="form-control" name="gambar"
                                                                accept="image/*">

                                                            <!-- Pratinjau gambar jika sudah ada -->
                                                            <?php if($item->gambar): ?>
                                                                <p class="mt-2">Gambar Saat Ini:</p>
                                                                <img src="<?php echo e(asset('storage/' . $item->gambar)); ?>"
                                                                    alt="Gambar Soal" class="img-fluid mt-2"
                                                                    style="max-width: 100px;">
                                                            <?php endif; ?>

                                                        </div>

                                                        <div class="form-group">
                                                            <label for="type_soal">Tipe Soal</label>
                                                            <select name="type_soal" class="form-control type-soal"
                                                                data-id="<?php echo e($item->id); ?>">
                                                                <option value="Objective"
                                                                    <?php echo e($item->type_soal == 'Objective' ? 'selected' : ''); ?>>
                                                                    Objective</option>
                                                                <option value="Essay"
                                                                    <?php echo e($item->type_soal == 'Essay' ? 'selected' : ''); ?>>
                                                                    Essay</option>
                                                                <option value="TrueFalse"
                                                                    <?php echo e($item->type_soal == 'TrueFalse' ? 'selected' : ''); ?>>
                                                                    True or False</option>
                                                            </select>
                                                        </div>

                                                        <div class="soal-fields" id="soal_fields_<?php echo e($item->id); ?>">
                                                            <?php if($item->type_soal == 'Objective'): ?>
                                                            <?php $choices = $item->pilihan_jawaban; ?>
                                                            <div class="form-group">
                                                                    <label>Pilihan Jawaban</label>
                                                                    <input type="text" name="pilihan_jawaban[A]"
                                                                        class="form-control mb-2" placeholder="Jawaban A"
                                                                        value="<?php echo e($choices['A'] ?? ''); ?>">
                                                                    <input type="text" name="pilihan_jawaban[B]"
                                                                        class="form-control mb-2" placeholder="Jawaban B"
                                                                        value="<?php echo e($choices['B'] ?? ''); ?>">
                                                                    <input type="text" name="pilihan_jawaban[C]"
                                                                        class="form-control mb-2" placeholder="Jawaban C"
                                                                        value="<?php echo e($choices['C'] ?? ''); ?>">
                                                                    <input type="text" name="pilihan_jawaban[D]"
                                                                        class="form-control mb-2" placeholder="Jawaban D"
                                                                        value="<?php echo e($choices['D'] ?? ''); ?>">
                                                                </div>

                                                                <div class="form-group">
                                                                    <label>Jawaban Benar</label>
                                                                    <select name="jawaban_benar" class="form-control">
                                                                        <option value="A"
                                                                            <?php echo e($item->jawaban_benar == 'A' ? 'selected' : ''); ?>>
                                                                            A</option>
                                                                        <option value="B"
                                                                            <?php echo e($item->jawaban_benar == 'B' ? 'selected' : ''); ?>>
                                                                            B</option>
                                                                        <option value="C"
                                                                            <?php echo e($item->jawaban_benar == 'C' ? 'selected' : ''); ?>>
                                                                            C</option>
                                                                        <option value="D"
                                                                            <?php echo e($item->jawaban_benar == 'D' ? 'selected' : ''); ?>>
                                                                            D</option>
                                                                    </select>
                                                                </div>
                                                            <?php elseif($item->type_soal == 'Essay'): ?>
                                                                <div class="form-group">
                                                                    <label>Jawaban Essay</label>
                                                                    <textarea name="jawaban_benar" class="form-control" rows="3"><?php echo e($item->jawaban_benar); ?></textarea>
                                                                </div>
                                                            <?php elseif($item->type_soal == 'TrueFalse'): ?>
                                                                <div class="form-group">
                                                                    <label>Jawaban Benar</label>
                                                                    <select name="jawaban_benar" class="form-control">
                                                                        <option value="true"
                                                                            <?php echo e($item->jawaban_benar == 'true' ? 'selected' : ''); ?>>
                                                                            Benar</option>
                                                                        <option value="false"
                                                                            <?php echo e($item->jawaban_benar == 'false' ? 'selected' : ''); ?>>
                                                                            Salah</option>
                                                                    </select>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Save
                                                            changes</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Data -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Soal</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('soal.store', ['kuis_id' => $kuis->id])); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <input type="hidden" name="kuis_id" value="<?php echo e($kuis->id); ?>">


                        <div class="form-group">
                            <label for="teks_soal">Teks Soal</label>
                            <textarea class="form-control" name="teks_soal"><?php echo e(old('teks_soal')); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="gambar">Unggah Gambar (Opsional)</label>
                            <input type="file" class="form-control <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                name="gambar" accept="image/*">
                            <?php $__errorArgs = ['gambar'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label for="exampleSelectGender">Tipe Soal</label>
                            <select name="type_soal" class="form-control  <?php $__errorArgs = ['type_soal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('type_soal')); ?>" id="type_soal">
                                <option value="">Pilih Type Soal</option>
                                <option value="Objective" <?php echo e(old('type_soal') == 'Objective' ? 'selected' : ''); ?>>
                                    Objective</option>
                                <option value="Essay" <?php echo e(old('type_soal') == 'Essay' ? 'selected' : ''); ?>>
                                    Essay</option>
                                <option value="TrueFalse" <?php echo e(old('type_soal') == 'TrueFalse' ? 'selected' : ''); ?>>
                                    True or False</option>
                            </select>
                            <?php $__errorArgs = ['type_soal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert">
                                    <strong><?php echo e($message); ?></strong>
                                </span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div id="soal_fields"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('type_soal').addEventListener('change', function() {
                let fieldContainer = document.getElementById('soal_fields');
                fieldContainer.innerHTML = '';

                if (this.value === 'Objective') {
                    fieldContainer.innerHTML = `
                    <div class="form-group">
                        <label>Pilihan Jawaban</label>
                        <input type="text" name="pilihan_jawaban[A]" class="form-control mb-2" placeholder="Jawaban A">
                        <input type="text" name="pilihan_jawaban[B]" class="form-control mb-2" placeholder="Jawaban B">
                        <input type="text" name="pilihan_jawaban[C]" class="form-control mb-2" placeholder="Jawaban C">
                        <input type="text" name="pilihan_jawaban[D]" class="form-control mb-2" placeholder="Jawaban D">
                    </div>

                    <div class="form-group">
                        <label>Jawaban Benar</label>
                        <select name="jawaban_benar" class="form-control">
                            <option value="">Pilih Jawaban Benar</option>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                            <option value="D">D</option>
                        </select>
                    </div>
                `;
                } else if (this.value === 'Essay') {
                    fieldContainer.innerHTML =
                        `<textarea name="jawaban_benar" class="form-control" rows="3" placeholder="Jawaban Essay"></textarea>`;
                } else if (this.value === 'TrueFalse') {
                    fieldContainer.innerHTML = `
                    <select name="jawaban_benar" class="form-control">
                        <option value="true">Benar</option>
                        <option value="false">Salah</option>
                    </select>
                `;
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/kuis/soal.blade.php ENDPATH**/ ?>