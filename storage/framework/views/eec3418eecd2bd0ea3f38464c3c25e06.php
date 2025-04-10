

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
                                <p class="card-title">Daftar Data Siswa</p>
                                <!-- Button to trigger modal -->
                                <a href="<?php echo e(route('siswa.create')); ?>" class="btn btn-primary">Tambah Data</a>
                            </div>

                            <!-- Dropdown Filter Section -->
                            <div class="row">
                                <!-- Tombol Download -->
                                <div class="col-md-3">
                                    <form method="GET" action="<?php echo e(route('siswa.export')); ?>">
                                        <input type="hidden" name="kelas_id" value="<?php echo e(request('kelas_id')); ?>">
                                        <!-- Masukkan filter kelas -->
                                        <button type="submit" class="btn btn-sm btn-inverse-success btn-icon-text">
                                            <i class="ti-download btn-icon-prepend"></i>
                                            Download XLSX
                                        </button>
                                    </form>
                                </div>


                                <!-- Filter Kelas -->
                                <div class="col-md-3 offset-md-6">
                                    <form method="GET" action="<?php echo e(route('siswa.index')); ?>"
                                        class="d-flex justify-content-end">
                                        <select name="kelas_id" id="kelas_id" class="form-control mr-2">
                                            <option value="">-- Semua Kelas --</option>
                                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($k->id); ?>"
                                                    <?php echo e(request('kelas_id') == $k->id ? 'selected' : ''); ?>>
                                                    <?php echo e($k->nama_kelas); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-info">
                                            <i class="ti-filter btn-icon-append"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            <!-- Table Section -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Kelas</th>
                                            <th>Foto</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                                <td> <?php echo e($user->name); ?> </td>
                                                <td> <?php echo e($user->username); ?> </td>
                                                <td> <?php echo e($user->email); ?> </td>
                                                <td> <?php echo e($user->siswa->kelas->nama_kelas ?? '-'); ?> </td>
                                                <td>
                                                    <?php if($user->foto): ?>
                                                        <img src="<?php echo e(asset('storage/foto_user/' . $user->foto)); ?>"
                                                            alt="Foto" width="50">
                                                    <?php else: ?>
                                                        No Image
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="<?php echo e(route('siswa.edit', $user->siswa->id)); ?>"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-3">Edit</a>

                                                        <form id="deleteForm<?php echo e($user->siswa->id); ?>"
                                                            action="<?php echo e(route('siswa.destroy', $user->siswa->id)); ?>"
                                                            method="POST" style="display: inline;">
                                                            <?php echo csrf_field(); ?>
                                                            <?php echo method_field('DELETE'); ?>
                                                            <button type="button"
                                                                class="btn btn-sm btn-outline-danger btn-fw"
                                                                onclick="confirmDelete('<?php echo e($user->siswa->id); ?>')">Delete</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php echo e($users->links()); ?> <!-- Tambahkan pagination -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/siswa/index.blade.php ENDPATH**/ ?>