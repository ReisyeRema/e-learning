

<?php $__env->startSection('title', 'Data Diri'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Data Diri Section -->
    <div class="data-diri-box">
        <h3>Data Diri</h3>
        <form action="<?php echo e(route('profile-siswa.update')); ?>" method="post" enctype="multipart/form-data">

            <?php echo csrf_field(); ?>
            <?php echo method_field('put'); ?>

            <div class="d-flex flex-column align-items-center mb-3">
                <div class="profile-picture">
                    <img id="previewFoto"
                        src="<?php echo e($user->foto ? asset('storage/foto_user/' . $user->foto) : asset('assets/img/profil.png')); ?>"
                        alt="Foto Profil" class="rounded-circle img-fluid" style="max-width: 150px; max-height: 150px;">
                </div>

                <button type="button" class="btn btn-sm btn-outline-success"
                    onclick="document.getElementById('uploadFoto').click();">
                    Ganti Foto Profil
                </button>

                <input id="uploadFoto" name="foto" type="file" accept="image/*" class="d-none"
                    onchange="previewImage(event)">
            </div>


            <div class="row mb-3">
                <label for="name" class="col-md-3 col-form-label text-left">Nama</label>
                <div class="col-md-9">
                    <input id="name" name="name" type="text" value="<?php echo e(old('name', $user->name)); ?>"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="username" class="col-md-3 col-form-label text-left">Username</label>
                <div class="col-md-9">
                    <input id="username" name="username" type="text" value="<?php echo e(old('username', $user->username)); ?>"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-md-3 col-form-label text-left">Email</label>
                <div class="col-md-9">
                    <input id="email" name="email" type="email" value="<?php echo e(old('email', $user->email)); ?>"
                        class="form-control" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="nis" class="col-md-3 col-form-label text-left">NIS</label>
                <div class="col-md-9">
                    <input id="nis" name="nis" type="text" value="<?php echo e(old('nis', $profile->nis ?? '')); ?>"
                        class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="kelas_id" class="col-md-3 col-form-label text-left">Kelas</label>
                <div class="col-md-9">
                    <select name="kelas_id" class="form-control <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="kelas_id">
                        <option value="">Pilih Kelas</option>
                        <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($item->id); ?>"
                                <?php echo e(old('kelas_id', $profile->kelas_id ?? '') == $item->id ? 'selected' : ''); ?>>
                                <?php echo e($item->nama_kelas); ?> 
                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="tempat_lahir" class="col-md-3 col-form-label text-left">Tempat Lahir</label>
                <div class="col-md-9">
                    <input id="tempat_lahir" name="tempat_lahir" type="text"
                        value="<?php echo e(old('tempat_lahir', $profile->tempat_lahir ?? '')); ?>" class="form-control">
                </div>
            </div>

            <div class="row mb-3">
                <label for="tanggalLahir" class="col-md-3 col-form-label text-left">Tanggal Lahir</label>
                <div class="col-md-9">
                    <div class="input-group">
                        <input id="tanggalLahir" name="tanggal_lahir" type="text"
                            value="<?php echo e(old('tanggal_lahir', $profile->tanggal_lahir ?? '')); ?>" class="form-control"
                            placeholder="YYYY-MM-DD">
                        <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="jenis_kelamin" class="col-md-3 col-form-label text-left">Jenis Kelamin</label>
                <div class="col-md-9">
                    <select id="jenis_kelamin" name="jenis_kelamin" class="form-control">
                        <option value="Laki-laki"
                            <?php echo e(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : ''); ?>>
                            Laki-laki</option>
                        <option value="Perempuan"
                            <?php echo e(old('jenis_kelamin', $profile->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : ''); ?>>
                            Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label for="alamat" class="col-md-3 col-form-label text-left">Alamat</label>
                <div class="col-md-9">
                    <textarea id="alamat" name="alamat" class="form-control"><?php echo e(old('alamat', $profile->alamat ?? '')); ?></textarea>
                </div>
            </div>


            <div class="col-md-12 text-end mt-3">

                <button class="btn btn-success mr-2"><?php echo e(__('Save')); ?></button>

                <?php if(session('status') === 'profile-updated'): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessAlert('Profile berhasil diperbarui!');
                        });
                    </script>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <!-- Ubah Password Section -->
    <div class="data-diri-box mt-5">
        <h3 class="mb-5">Informasi Akun</h3>

        <form action="<?php echo e(route('password.update')); ?>" method="post" enctype="multipart/form-data">

            <?php echo csrf_field(); ?>
            <?php echo method_field('put'); ?>

            <div class="col-md-12 mb-3">
                <label for="current_password" class="pb-2">Password Lama</label>
                <div class="input-group">
                    <input name="current_password" type="password" class="form-control"
                        id="update_password_current_password">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_current_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <?php if($errors->updatePassword->has('current_password')): ?>
                    <span class="text-danger mt-2 d-block">
                        <?php echo e($errors->updatePassword->first('current_password')); ?>

                    </span>
                <?php endif; ?>
            </div>

            <div class="col-md-12 mb-3">
                <label for="password" class="pb-2">Password Baru</label>
                <div class="input-group">
                    <input id="update_password_password" name="password" type="password" class="form-control">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <?php if($errors->updatePassword->has('password')): ?>
                    <span class="text-danger mt-2 d-block">
                        <?php echo e($errors->updatePassword->first('password')); ?>

                    </span>
                <?php endif; ?>
            </div>

            <div class="col-md-12 mb-3">
                <label for="password_confirmation" class="pb-2">Konfirmasi Password</label>
                <div class="input-group">
                    <input name="password_confirmation" type="password" class="form-control"
                        id="update_password_password_confirmation">
                    <div class="input-group-append">
                        <button type="button" class="btn btn-outline-secondary toggle-password"
                            data-target="update_password_password_confirmation">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                </div>
                <?php if($errors->updatePassword->has('password_confirmation')): ?>
                    <span class="text-danger mt-2 d-block">
                        <?php echo e($errors->updatePassword->first('password_confirmation')); ?>

                    </span>
                <?php endif; ?>
            </div>



            <div class="col-md-12 text-end mt-3">

                <button class="btn btn-success mr-2"><?php echo e(__('Save')); ?></button>

                <?php if(session('status') === 'password-updated'): ?>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            showSuccessAlert('Password berhasil diperbarui!');
                        });
                    </script>
                <?php endif; ?>
            </div>
        </form>

    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/dataDiri/edit.blade.php ENDPATH**/ ?>