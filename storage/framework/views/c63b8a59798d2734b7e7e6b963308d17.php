

<?php $__env->startSection('title', 'Profile Sekolah'); ?>

<?php $__env->startSection('content'); ?>
    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <h4 class="card-title">Profile Sekolah</h4>

                                    <form action="<?php echo e(route('profilesekolah.update')); ?>" method="POST" class="forms-sample"
                                        enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>


                                        <div class="form-group">
                                            <label for="exampleInputUsername">Nama Sekolah</label>
                                            <input name="nama_sekolah"
                                                class="form-control <?php $__errorArgs = ['nama_sekolah'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('nama_sekolah')); ?>" type="text"
                                                id="exampleInputnama_sekolah" placeholder="nama_sekolah">
                                            <?php if($errors->has('nama_sekolah')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('nama_pkk')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleTextarea1">Alamat</label>
                                            <textarea name="alamat" class="form-control" id="exampleTextarea1" rows="4"><?php echo e(old('alamat')); ?></textarea>
                                            <?php if($errors->has('alamat')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('alamat')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Akreditasi</label>
                                            <input name="akreditas"
                                                class="form-control <?php $__errorArgs = ['akreditas'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('akreditas')); ?>" type="text" id="exampleInputakreditas1"
                                                placeholder="Alamat">
                                            <?php if($errors->has('akreditas')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('akreditas')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">Email</label>
                                            <input name="email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('email')); ?>" type="email" id="exampleInputemail1"
                                                placeholder="Alamat">
                                            <?php if($errors->has('email')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('email')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="exampleInputName1">NO HP</label>
                                            <input name="no_hp" class="form-control <?php $__errorArgs = ['no_hp'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('no_hp')); ?>" type="text" id="exampleInputno_hp1"
                                                placeholder="Alamat">
                                            <?php if($errors->has('no_hp')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('no_hp')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label>Logo</label>
                                            <div class="tampil-foto mb-2"></div>
                                            <input type="file" name="foto"
                                                class="form-control  <?php $__errorArgs = ['foto'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*"
                                                onchange="preview('.tampil-foto', this.files[0], 300)">
                                            <?php if($errors->has('foto')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('foto')); ?></div>
                                            <?php endif; ?>
                                            <small class="text-muted">Maximum file size: 2 MB</small>
                                        </div>

                                        <div class="form-group">
                                            <label for="latitude">Latitude</label>
                                            <input name="latitude" type="text" step="any"
                                                class="form-control <?php $__errorArgs = ['latitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('latitude')); ?>" placeholder="-6.200000">
                                            <?php if($errors->has('latitude')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('latitude')); ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-group">
                                            <label for="longitude">Longitude</label>
                                            <input name="longitude" type="text" step="any"
                                                class="form-control <?php $__errorArgs = ['longitude'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                value="<?php echo e(old('longitude')); ?>" placeholder="106.816666">
                                            <?php if($errors->has('longitude')): ?>
                                                <div class="text-danger"><?php echo e($errors->first('longitude')); ?></div>
                                            <?php endif; ?>
                                        </div>


                                        <div class="form-group d-flex justify-content-end">
                                            <button type="submit" class="btn btn-primary mr-2">Submit</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
    <script>
        $(function() {
            showData();
            $('.form-setting').validator().on('submit', function(e) {
                if (!e.preventDefault()) {
                    $.ajax({
                            url: $('.form-setting').attr('action'),
                            type: $('.form-setting').attr('method'),
                            data: new FormData($('.form-setting')[0]),
                            async: false,
                            processData: false,
                            contentType: false
                        })
                        .done(response => {
                            showData();
                            $('.alert').fadeOut();
                        })
                        .fail(errors => {
                            alert('Tidak dapat menyimpan data');
                            return;
                        });
                }
            });
        });

        function showData() {
            $.get('<?php echo e(route('profilesekolah.show')); ?>')
                .done(response => {
                    $('[name=nama_sekolah]').val(response.nama_sekolah);
                    $('[name=alamat]').val(response.alamat);
                    $('[name=akreditas]').val(response.akreditas);
                    $('[name=email]').val(response.email);
                    $('[name=no_hp]').val(response.no_hp);
                    $('.tampil-foto').html(
                        `<img src="<?php echo e(url('storage/logo_sekolah/')); ?>/${response.foto}" width="100">`);
                    $('[name=latitude]').val(response.latitude);
                    $('[name=longitude]').val(response.longitude);

                })
                .fail(errors => {
                    alert('Tidak dapat menampilkan data');
                    return;
                })
        }
    </script>


    <script>
        function previewImage(event) {
            const input = event.target;
            const reader = new FileReader();

            reader.onload = function() {
                const preview = document.getElementById('previewFoto');
                preview.src = reader.result; // Tampilkan gambar baru
            };

            if (input.files && input.files[0]) {
                reader.readAsDataURL(input.files[0]); // Baca file yang diunggah
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/profilSekolah/index.blade.php ENDPATH**/ ?>