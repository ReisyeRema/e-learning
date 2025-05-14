

<?php $__env->startSection('title', 'Data Absensi'); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo $__env->make('components.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Absensi Siswa <?php echo e($kelasData->nama_kelas); ?></h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>
                    <a href="<?php echo e(route('absensi.export', ['pembelajaran_id' => $pembelajaran->id])); ?>" class="btn btn-sm btn-success mb-3">
                        <i class="fa fa-download"></i> Export Absensi Kelas <?php echo e($kelasData->nama_kelas); ?>

                    </a>    
                    <div class="table-responsive">
                        <table id="myTable" class="display expandable-table" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="text-align: center">No</th>
                                    <th>Pertemuan</th>
                                    <th>Tanggal</th>
                                    <th>Jam Mulai - Jam Selesai</th>
                                    <th width="15%">
                                        <center>Aksi</center>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $absensi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                        <td><?php echo e($item->pertemuan->judul ?? '-'); ?></td>
                                        <td><?php echo e(\Carbon\Carbon::parse($item->tanggal)->format('d M Y')); ?></td>
                                        <td><?php echo e($item->jam_mulai); ?> - <?php echo e($item->jam_selesai); ?></td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <a href="<?php echo e(route('detail-absensi.index', [
                                                    'mapel' => Str::slug($pembelajaran->nama_mapel, '-'),
                                                    'kelas' => Str::slug($kelasData->nama_kelas, '-'),
                                                    'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                                                    'absensi_id' => $item->id,
                                                ])); ?>"
                                                    class="btn btn-sm btn-outline-primary btn-fw mr-3">Isi Absensi</a>
                                                <button type="button" class="btn btn-sm btn-outline-success btn-fw mr-3"
                                                    data-toggle="modal" data-target="#editModal<?php echo e($item->id); ?>">
                                                    Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-fw"
                                                    onclick="confirmDelete(<?php echo e($item->id); ?>)">
                                                    Delete
                                                </button>
                                                <form id="deleteForm<?php echo e($item->id); ?>"
                                                    action="<?php echo e(route('absensi.destroy', $item->id)); ?>" method="POST"
                                                    style="display: none;">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Modal Edit Absensi -->
                                    <div class="modal fade" id="editModal<?php echo e($item->id); ?>" tabindex="-1" role="dialog"
                                        aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <form action="<?php echo e(route('absensi.update', $item->id)); ?>" method="POST">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('PUT'); ?>
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Absensi</h5>
                                                        <button type="button" class="close"
                                                            data-dismiss="modal"><span>&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Tanggal</label>
                                                            <input type="date" name="tanggal"
                                                                value="<?php echo e(old('tanggal', \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d'))); ?>"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jam Mulai</label>
                                                            <input type="time" name="jam_mulai"
                                                                value="<?php echo e(old('jam_mulai', $item->jam_mulai)); ?>"
                                                                class="form-control">
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Jam Selesai</label>
                                                            <input type="time" name="jam_selesai"
                                                                value="<?php echo e(old('jam_selesai', $item->jam_selesai)); ?>"
                                                                class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-primary">Perbarui</button>
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

    <!-- Modal for adding new Absensi -->
    <div class="modal fade" id="addKurikulumModal" tabindex="-1" aria-labelledby="addKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Absensi <?php echo e($kelasData->nama_kelas); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form method="POST" action="<?php echo e(route('absensi.store', ['pembelajaran_id' => $pembelajaran->id])); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        
                        <div class="form-group">
                            <label for="tanggal">Tanggal</label>
                            <input type="date" name="tanggal" class="form-control <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                value="<?php echo e(old('tanggal')); ?>">
                            <?php $__errorArgs = ['tanggal'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        
                        <div class="form-group">
                            <label>Waktu</label>
                            <div class="d-flex">
                                <input type="time" name="jam_mulai"
                                    class="form-control mr-2 <?php $__errorArgs = ['jam_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('jam_mulai')); ?>">
                                <span class="align-self-center mr-2">sampai</span>
                                <input type="time" name="jam_selesai"
                                    class="form-control <?php $__errorArgs = ['jam_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                    value="<?php echo e(old('jam_selesai')); ?>">
                            </div>
                            <?php $__errorArgs = ['jam_mulai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <?php $__errorArgs = ['jam_selesai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback d-block"><strong><?php echo e($message); ?></strong></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <hr>

                        
                        <div class="form-group row align-items-center">
                            <label class="col-md-2 col-form-label font-weight-bold">Multisesi</label>
                            <div class="col-md-10">
                                <div class="form-check">
                                    <input type="hidden" name="is_multisession" value="0">
                                    <input class="form-check-input" type="checkbox" name="is_multisession"
                                        value="1" id="multiSessionCheck"
                                        <?php echo e(old('is_multisession') ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="multiSessionCheck">
                                        Ulangi sesi di atas sebagai berikut:
                                    </label>
                                </div>
                            </div>
                        </div>

                        
                        <div id="multisession-options" style="display: none;">
                            
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Ulangi Pada</label>
                                <div class="col-md-10 d-flex flex-wrap">
                                    <?php $__currentLoopData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="form-check mr-4">
                                            <input class="form-check-input" type="checkbox" name="ulangi_pada[]"
                                                value="<?php echo e($day); ?>" id="<?php echo e($day); ?>"
                                                <?php echo e(is_array(old('ulangi_pada')) && in_array($day, old('ulangi_pada')) ? 'checked' : ''); ?>>
                                            <label class="form-check-label ml-1 mr-5"
                                                for="<?php echo e($day); ?>"><?php echo e($day); ?></label>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <?php $__errorArgs = ['ulangi_pada'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <div class="col-md-10 offset-md-2">
                                        <span class="invalid-feedback d-block"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    </div>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            
                            <div class="form-group row align-items-center">
                                <label class="col-md-2 col-form-label">Ulangi Sampai Tanggal</label>
                                <div class="col-md-10">
                                    <input type="date"
                                        class="form-control <?php $__errorArgs = ['ulangi_sampai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        id="ulangi_sampai" name="ulangi_sampai" value="<?php echo e(old('ulangi_sampai')); ?>">
                                    <?php $__errorArgs = ['ulangi_sampai'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback"
                                            role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                            </div>
                        </div>


                        
                        <input type="hidden" name="pembelajaran_id" value="<?php echo e($pembelajaran->id); ?>">

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>



<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(document).ready(function() {
            function toggleMultisessionOptions() {
                if ($('#multiSessionCheck').is(':checked')) {
                    $('#multisession-options').slideDown();
                } else {
                    $('#multisession-options').slideUp();
                }
            }

            // Cek status saat halaman dimuat
            toggleMultisessionOptions();

            // Toggle saat checkbox diubah
            $('#multiSessionCheck').change(function() {
                toggleMultisessionOptions();
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/absensi/show.blade.php ENDPATH**/ ?>