

<?php $__env->startSection('title', 'Data Kuis'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <!-- Button Section -->
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <p class="card-title">Daftar Kuis</p>
                                <!-- Button to trigger modal -->
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#addKelasModal">
                                    Tambah Data
                                </button>
                            </div>
                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Judul</th>
                                            <th>Kategori</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $kuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                                
                                                <td> <?php echo e($item->judul); ?> </td>
                                                <td> <?php echo e($item->kategori); ?> </td>
                                                <td>
                                                    <div class="d-flex align-items-center">

                                                        <!-- Button to navigate to another page -->
                                                        <a href="<?php echo e(route('soal.index', ['kuis_id' => $item->id])); ?>"
                                                            class="btn btn-sm btn-outline-primary btn-fw mr-3">
                                                            +
                                                        </a>

                                                        <!-- Button to trigger modal edit -->
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                            data-toggle="modal"
                                                            data-target="#editKelasModal<?php echo e($item->id); ?>">
                                                            Edit
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                            onclick="confirmDelete(<?php echo e($item->id); ?>)">
                                                            Delete
                                                        </button>
                                                        <form id="deleteForm<?php echo e($item->id); ?>"
                                                            action="<?php echo e(route('kuis.destroy', $item->id)); ?>" method="POST"
                                                            style="display: none;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal for Editing Kelas -->
                                            <div class="modal fade" id="editKelasModal<?php echo e($item->id); ?>" tabindex="-1"
                                                aria-labelledby="editKelasModalLabel<?php echo e($item->id); ?>" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="editKelasModalLabel<?php echo e($item->id); ?>">
                                                                Edit Kelas</h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="<?php echo e(route('kuis.update', $item->id)); ?>"
                                                            method="POST">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('PUT'); ?>
                                                            <div class="modal-body">

                                                                <div class="form-group">
                                                                    <label for="judul">Judul</label>
                                                                    <input type="text"
                                                                        class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                        id="judul" name="judul"
                                                                        value="<?php echo e(old('judul', $item->judul)); ?>">
                                                                    <?php $__errorArgs = ['judul'];
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
                                                                    <label
                                                                        for="kategori-<?php echo e($item->id); ?>">Kategori</label>
                                                                    <select name="kategori"
                                                                        class="form-control kategori-edit"
                                                                        id="kategori-<?php echo e($item->id); ?>">
                                                                        <option value="">Pilih Kategori</option>
                                                                        <option value="Kuis"
                                                                            <?php echo e(old('kategori', $item->kategori) == 'Kuis' ? 'selected' : ''); ?>>
                                                                            Kuis</option>
                                                                        <option value="Ujian Mid"
                                                                            <?php echo e(old('kategori', $item->kategori) == 'Ujian Mid' ? 'selected' : ''); ?>>
                                                                            Ujian Mid</option>
                                                                        <option value="Ujian Akhir"
                                                                            <?php echo e(old('kategori', $item->kategori) == 'Ujian Akhir' ? 'selected' : ''); ?>>
                                                                            Ujian Akhir</option>
                                                                    </select>
                                                                </div>

                                                                <div class="form-group materi-form-group"
                                                                    id="materi-form-group-<?php echo e($item->id); ?>">
                                                                    <label for="materi-<?php echo e($item->id); ?>">Materi</label>
                                                                    <select name="materi_id" class="form-control"
                                                                        id="materi-<?php echo e($item->id); ?>">
                                                                        <option value="">Pilih Materi</option>
                                                                        <?php $__currentLoopData = $materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <option value="<?php echo e($m->id); ?>"
                                                                                <?php echo e(old('materi_id', $item->materi_id) == $m->id ? 'selected' : ''); ?>>
                                                                                <?php echo e($m->judul); ?>

                                                                            </option>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                    </select>
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
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new kelas -->
    <div class="modal fade" id="addKelasModal" tabindex="-1" aria-labelledby="addKelasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKelasModalLabel">Tambah Kelas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('kuis.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="judul">Judul</label>
                            <input type="text" class="form-control <?php $__errorArgs = ['judul'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="judul" name="judul" value="<?php echo e(old('judul')); ?>">
                            <?php $__errorArgs = ['judul'];
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
                            <label for="kategori">Kategori</label>
                            <select name="kategori" class="form-control <?php $__errorArgs = ['kategori'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="kategori">
                                <option value="">Pilih Kategori</option>
                                <option value="Kuis" <?php echo e(old('kategori') == 'Kuis' ? 'selected' : ''); ?>>Kuis</option>
                                <option value="Ujian Mid" <?php echo e(old('kategori') == 'Ujian Mid' ? 'selected' : ''); ?>>Ujian Mid
                                </option>
                                <option value="Ujian Akhir" <?php echo e(old('kategori') == 'Ujian Akhir' ? 'selected' : ''); ?>>Ujian
                                    Akhir</option>
                            </select>
                            <?php $__errorArgs = ['kategori'];
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

                        <div class="form-group" id="materi-form-group">
                            <label for="exampleSelectKelas">Materi</label>
                            <select name="materi_id" class="form-control <?php $__errorArgs = ['materi_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="exampleSelectguru">
                                <option value="">Pilih Materi</option>
                                <?php $__currentLoopData = $materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"
                                        <?php echo e(old('materi_id') == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e($item->judul); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['materi_id'];
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
        $(document).ready(function() {
            function toggleMateriForm() {
                let kategori = $('#kategori').val();
                if (kategori === 'Kuis') {
                    $('#materi-form-group').show();
                } else {
                    $('#materi-form-group').hide();
                    $('#exampleSelectguru').val('');
                }
            }

            // Jalankan saat halaman dibuka
            toggleMateriForm();

            // Jalankan saat dropdown berubah
            $('#kategori').on('change', function() {
                toggleMateriForm();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            // Fungsi untuk show/hide materi berdasarkan kategori
            function toggleEditMateriForm(id) {
                const kategori = $(`#kategori-${id}`).val();
                const materiGroup = $(`#materi-form-group-${id}`);
                const materiSelect = $(`#materi-${id}`);

                if (kategori === 'Kuis') {
                    materiGroup.show();
                } else {
                    materiGroup.hide();
                    materiSelect.val('');
                }
            }

            // Loop semua modal edit dan inisialisasi berdasarkan data
            <?php $__currentLoopData = $kuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                toggleEditMateriForm(<?php echo e($item->id); ?>);
                $(`#kategori-<?php echo e($item->id); ?>`).on('change', function() {
                    toggleEditMateriForm(<?php echo e($item->id); ?>);
                });
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/kuis/index.blade.php ENDPATH**/ ?>