

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
                                <a href="<?php echo e(route('siswa.create')); ?>" class="btn btn-primary">Tambah Data</a>
                            </div>

                            <!-- Dropdown Filter Section -->
                            <div class="row">
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
                            

                            <!-- Tombol Verifikasi -->
                            <div class="mb-3 mt-3">
                                <button type="button" class="btn btn-outline-success" onclick="submitBulkVerification()">
                                    Verifikasi yang Dipilih
                                </button>

                                <button type="button" class="btn btn-outline-danger" onclick="submitBulkUnverification()">
                                    Batalkan Verifikasi
                                </button>
                            </div>


                            <!-- Form Verifikasi -->
                            <form id="bulkVerifyForm" method="POST" action="<?php echo e(route('siswa.verify.multiple')); ?>"
                                style="display: none;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="user_ids" id="selectedUserIdsVerify">
                            </form>

                            <!-- Form Batalkan Verifikasi -->
                            <form id="bulkUnverifyForm" method="POST" action="<?php echo e(route('siswa.unverify.multiple')); ?>"
                                style="display: none;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <input type="hidden" name="user_ids" id="selectedUserIdsUnverify">
                            </form>


                            <!-- Tabel Siswa -->
                            <div class="table-responsive">
                                <table id="myTable" class="display expandable-table" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th style="text-align: center">No</th>
                                            <th>Nama</th>
                                            <th>Username</th>
                                            <th>Email</th>
                                            <th>Kelas</th>
                                            <th>Status Verifikasi</th>
                                            <th width="15%">
                                                <center>Aksi</center>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="checkItem" value="<?php echo e($user->id); ?>">
                                                </td>
                                                <td style="text-align: center"> <?php echo e($loop->iteration); ?> </td>
                                                <td> <?php echo e($user->name); ?> </td>
                                                <td> <?php echo e($user->username); ?> </td>
                                                <td> <?php echo e($user->email); ?> </td>
                                                <td> <?php echo e($user->siswa->kelas->nama_kelas ?? '-'); ?> </td>
                                                <td>
                                                    <?php if($user->is_verified): ?>
                                                        <span class="badge badge-success ml-2">Terverifikasi</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-danger ml-2">Tidak Terverifikasi</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <a href="<?php echo e(route('siswa.edit', $user->siswa->id)); ?>"
                                                            class="btn btn-sm btn-outline-success btn-fw mr-2">Edit</a>

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

                            <?php echo e($users->links()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        // Checkbox "Pilih Semua"
        document.getElementById('checkAll').addEventListener('change', function() {
            const checked = this.checked;
            document.querySelectorAll('.checkItem').forEach(cb => cb.checked = checked);
        });

        // Fungsi Submit Verifikasi
        function submitBulkVerification() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Pilih setidaknya satu siswa untuk diverifikasi.');
                return;
            }
            document.getElementById('selectedUserIdsVerify').value = selected.join(',');
            document.getElementById('bulkVerifyForm').submit();
        }

        // Fungsi Submit Unverifikasi
        function submitBulkUnverification() {
            const selected = getSelectedIds();
            if (selected.length === 0) {
                alert('Pilih setidaknya satu siswa untuk dibatalkan verifikasinya.');
                return;
            }
            document.getElementById('selectedUserIdsUnverify').value = selected.join(',');
            document.getElementById('bulkUnverifyForm').submit();
        }

        // Ambil ID terpilih
        function getSelectedIds() {
            return Array.from(document.querySelectorAll('.checkItem:checked')).map(cb => cb.value);
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/siswa/index.blade.php ENDPATH**/ ?>