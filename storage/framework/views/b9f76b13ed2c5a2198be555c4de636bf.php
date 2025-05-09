

<?php $__env->startSection('title', 'Data Pertemuan Tugas'); ?>

<style>
    .pertemuan-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .pertemuan-item:hover {
        background-color: #F7F7F7;
        /* Biru muda saat hover */
    }

    .pertemuan-item.active {
        background-color: #F7F7F7 !important;
        /* Biru saat aktif */
        color: rgb(0, 0, 0) !important;
        font-weight: bold;
    }

    .materi-item {
        cursor: pointer;
        transition: background-color 0.3s;
    }

    .materi-item:hover {
        background-color: #F7F7F7;
    }

    .materi-item.active {
        background-color: #007bff !important;
        color: white !important;
        font-weight: bold;
    }
</style>

<?php $__env->startSection('content'); ?>
    <div class="row">

        <?php echo $__env->make('components.nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h4 class="mb-3">Daftar Tugas Siswa <?php echo e($kelasData->nama_kelas); ?></h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>
                    

                    <a href="<?php echo e(route('tugas.export', ['pembelajaran_id' => $pembelajaran->id])); ?>" class="btn btn-sm btn-success mb-3">
                        <i class="fa fa-download"></i> Export Nilai Tugas
                    </a>
                    

                    <!-- Sidebar dan Content -->
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-12 col-md-3 mb-3">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <ul class="list-group" id="sidebar-pertemuan">
                                        <h5 class="mb-3">Daftar Pertemuan Tugas</h5>
                                        
                                        <?php $__currentLoopData = $pertemuan; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="list-group-item d-flex justify-content-between align-items-center pertemuan-item"
                                                data-pertemuan="<?php echo e($item->id); ?>"
                                                data-pembelajaran="<?php echo e($pembelajaran->id); ?>">
                                                <?php echo e($item->judul); ?>

                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Isi Pertemuan Tugas -->
                        <div class="col-md-9">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat tugas yang
                                        diinputkan</h5>
                                    <div class="list-group" id="tugas-container"
                                        data-mapel="<?php echo e(strtolower(str_replace(' ', '-', $pembelajaran->nama_mapel))); ?>"
                                        data-kelas="<?php echo e(strtolower(str_replace(' ', '-', $pembelajaran->kelas->nama_kelas))); ?>"
                                        data-tahun="<?php echo e(str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun)); ?>">
                                        <!-- Tugas akan dimasukkan di sini -->
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for adding new kurikulum -->
    <div class="modal fade" id="addKurikulumModal" tabindex="-1" aria-labelledby="addKurikulumModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Tugas <?php echo e($kelasData->nama_kelas); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('pertemuan-tugas.store', ['pembelajaran_id' => $pembelajaran->id])); ?>" method="POST"
                    enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Pertemuan</label>
                            <select name="pertemuan_id" class="form-control <?php $__errorArgs = ['pertemuan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="exampleSelectguru">
                                <option value="">Pilih Pertemuan</option>
                                <?php $__currentLoopData = $pertemuanSemua; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"
                                        <?php echo e(old('pertemuan_id') == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e($item->judul); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['pertemuan_id'];
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

                        <input type="hidden" name="pembelajaran_id" value="<?php echo e($pembelajaran->id); ?>">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Tugas</label>
                            <select name="tugas_id" class="form-control <?php $__errorArgs = ['tugas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="exampleSelectguru">
                                <option value="">Pilih Tugas</option>
                                <?php $__currentLoopData = $tugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"
                                        <?php echo e(old('tugas_id') == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e($item->judul); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['tugas_id'];
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
                            <label for="deadline">Deadline Tugas</label>
                            <input type="datetime-local" class="form-control <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="deadline" name="deadline" value="<?php echo e(old('deadline')); ?>">
                            <?php $__errorArgs = ['deadline'];
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


    <!-- Modal Edit Materi -->
    <div class="modal fade" id="editTugasModal" tabindex="-1" aria-labelledby="editTugasModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editTugasForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editTugasModalLabel">Edit Materi</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pembelajaran_id" value="<?php echo e($pembelajaran->id); ?>">
                        <input type="hidden" name="pertemuan_tugas_id" id="editPertemuanTugasId">

                        <div class="form-group">
                            <label for="editPertemuanSelect">Pertemuan</label>
                            <select name="pertemuan_id" id="editPertemuanSelect" class="form-control">
                                <?php $__currentLoopData = $pertemuanSemua; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->judul); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editTugasSelect">Tugas</label>
                            <select name="tugas_id" id="editTugasSelect" class="form-control">
                                <?php $__currentLoopData = $tugas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->judul); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editDeadline">Deadline Tugas</label>
                            <input type="datetime-local" class="form-control <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="editDeadline" name="deadline" value="<?php echo e(old('deadline')); ?>">
                            <?php $__errorArgs = ['deadline'];
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
                        <button type="button" class="btn btn-light" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {
            $(".pertemuan-item").click(function() {
                let pertemuanId = $(this).data("pertemuan");
                let pembelajaranId = $(this).data("pembelajaran");
                let pertemuanJudul = $(this).text().trim();

                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");

                $("#judul-pertemuan").text(pertemuanJudul);

                $.ajax({
                    url: `/guru/tugas/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function(response) {
                        let tugasContainer = $("#tugas-container");
                        tugasContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                let fileUrl = item.tugas.file_path ?
                                    `https://drive.google.com/file/d/${item.tugas.file_path}/view` :
                                    "#";

                                let tugasItem = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 tugas-item"
                                        data-id="${item.id}" data-file-url="${fileUrl}">
                                        <div>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span class="ml-2">${item.tugas.judul}</span>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-info btn-sm lihat-siswa" data-id="${item.tugas.id}">
                                                <i class="fas fa-users"></i> Lihat Siswa
                                            </button>
                                            <button class="btn btn-warning btn-sm edit-tugas" 
                                                data-toggle="modal" 
                                                data-target="#editTugasModal" 
                                                data-id="${item.id}" 
                                                data-tugas-id="${item.tugas.id}" 
                                                data-pertemuan-id="${item.pertemuan_id}" 
                                                data-deadline="${item.deadline}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-tugas" data-id="${item.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>`;

                                tugasContainer.append(tugasItem);
                            });

                            // Buka file
                            $(document).on("click", ".tugas-item", function(e) {

                                if ($(e.target).closest(".edit-tugas, .delete-tugas")
                                    .length > 0) {
                                    return;
                                }

                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });

                            $(".lihat-siswa").click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                let container = $("#tugas-container");
                                let mapelSlug = container.data("mapel");
                                let kelasSlug = container.data("kelas");
                                let tahunSlug = container.data("tahun");

                                let tugasId = $(this).data(
                                    "id"); // Ambil ID tugas yg diklik

                                let redirectUrl =
                                    `/guru/submit-tugas/${mapelSlug}/${kelasSlug}/${tahunSlug}/list-tugas?tugas_id=${tugasId}`;
                                window.location.href = redirectUrl;
                            });


                            // Tampilkan data ke dalam modal edit saat tombol edit diklik
                            $(document).on("click", ".edit-tugas", function(e) {
                                e
                                    .stopPropagation(); // Penting: mencegah klik masuk ke tugas-item dan membuka file

                                let id = $(this).data("id");
                                let tugasId = $(this).data("tugas-id");
                                let pertemuanId = $(this).data("pertemuan-id");
                                let deadline = $(this).data("deadline");

                                $("#editPertemuanTugasId").val(id);
                                $("#editPertemuanSelect").val(pertemuanId);
                                $("#editTugasSelect").val(tugasId);
                                $("#editDeadline").val(deadline);

                                let actionUrl = `/guru/pertemuan-tugas/${id}`;
                                $("#editTugasForm").attr("action", actionUrl);
                            });


                            // Hapus tugas
                            $(".delete-tugas").click(function(e) {
                                e.preventDefault();
                                e.stopPropagation();

                                let tugasId = $(this).data("id");
                                let parentItem = $(this).closest(".tugas-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "tugas ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-tugas/" +
                                                tugasId,
                                            type: "DELETE",
                                            data: {
                                                _token: "<?php echo e(csrf_token()); ?>"
                                            },
                                            success: function(
                                                response) {
                                                if (response
                                                    .success) {
                                                    parentItem
                                                        .fadeOut(
                                                            300,
                                                            function() {
                                                                $(this)
                                                                    .remove();
                                                            });
                                                    Swal.fire(
                                                        "Terhapus!",
                                                        "tugas telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus tugas.",
                                                        "error");
                                                }
                                            },
                                            error: function() {
                                                Swal.fire("Error!",
                                                    "Terjadi kesalahan saat menghapus tugas.",
                                                    "error");
                                            }
                                        });
                                    }
                                });
                            });

                        } else {
                            tugasContainer.append(
                                "<p class='text-muted'>Tidak ada materi untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/tugas/show.blade.php ENDPATH**/ ?>