

<?php $__env->startSection('title', 'Data Pertemuan Kuis'); ?>

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
                        <h4 class="mb-3">Daftar Kuis Siswa <?php echo e($kelasData->nama_kelas); ?></h4>
                        <!-- Button to trigger modal -->
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal"
                            data-target="#addKurikulumModal">
                            Tambahkan
                        </button>
                    </div>


                    <!-- Sidebar dan Content -->
                    <div class="row">
                        <!-- Sidebar -->
                        <div class="col-12 col-md-3 mb-3">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <ul class="list-group" id="sidebar-pertemuan">
                                        <h5 class="mb-3">Daftar Pertemuan Kuis</h5>
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

                        <!-- Isi Pertemuan Materi -->
                        <div class="col-md-9">
                            <div class="card shadow-sm" style="background-color: #F2F9FF; overflow-y: auto;">
                                <div class="card-body">
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat kuis yang
                                        diinputkan</h5>
                                    <div class="list-group" id="kuis-container">
                                        <!-- Materi akan dimasukkan di sini -->
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
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Kuis <?php echo e($kelasData->nama_kelas); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('pertemuan-kuis.store', ['pembelajaran_id' => $pembelajaran->id])); ?>" method="POST"
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
                            <label for="exampleSelectKelas">Kuis</label>
                            <select name="kuis_id" class="form-control <?php $__errorArgs = ['kuis_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                id="exampleSelectguru">
                                <option value="">Pilih Kuis</option>
                                <?php $__currentLoopData = $kuis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"
                                        <?php echo e(old('kuis_id') == $item->id ? 'selected' : ''); ?>>
                                        <?php echo e($item->judul); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['kuis_id'];
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $(".pertemuan-item").click(function() {
                let pertemuanId = $(this).data("pertemuan");
                let pembelajaranId = $(this).data("pembelajaran");
                let pertemuanJudul = $(this).text().trim();

                // Hapus class 'active' dari semua item dan tambahkan ke yang diklik
                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");

                // Update judul pertemuan
                $("#judul-pertemuan").text(pertemuanJudul);

                $.ajax({
                    url: `/guru/kuis/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function(response) {
                        let kuisContainer = $("#kuis-container");
                        kuisContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                // let fileUrl = item.materi.file_path ? "/storage/" + item
                                //     .materi.file_path : "#";

                                let kuisItem = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 kuis-item"
                                        data-id="${item.id}">
                                        <div>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span class="ml-2">${item.kuis.judul}</span><br>
                                            <small class="ml-4">Token: <code id="token-${item.id}">${item.token}</code></small>
                                        </div>
                                        <div>
                                            <button class="btn btn-light btn-sm copy-token" data-token="${item.token}" data-token-id="${item.id}">
                                                <i class="fas fa-copy"></i> Salin
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-kuis" data-id="${item.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>`;


                                kuisContainer.append(kuisItem);
                            });

                            // Event klik untuk membuka file
                            $(".kuis-item").click(function() {
                                let fileUrl = $(this).data("file-url");
                                if (fileUrl !== "#") {
                                    window.open(fileUrl, "_blank");
                                } else {
                                    alert("File tidak tersedia.");
                                }
                            });

                            // Event klik untuk hapus kuis dengan SweetAlert
                            $(".delete-kuis").click(function(e) {
                                e.preventDefault();
                                e
                                    .stopPropagation(); // Mencegah event bubbling ke elemen .kuis-item

                                let kuisId = $(this).data("id");
                                let parentItem = $(this).closest(".kuis-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "kuis ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-kuis/" +
                                                kuisId,
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
                                                        "kuis telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus kuis.",
                                                        "error"
                                                    );
                                                }
                                            },
                                            error: function() {
                                                Swal.fire(
                                                    "Error!",
                                                    "Terjadi kesalahan saat menghapus kuis.",
                                                    "error"
                                                );
                                            }
                                        });
                                    }
                                });
                            });


                            // Handler tombol salin token
                            $(".copy-token").click(function(e) {
                                e.stopPropagation(); // Biar gak trigger klik item kuis
                                let token = $(this).data("token");

                                // Salin ke clipboard
                                navigator.clipboard.writeText(token).then(function() {
                                    Swal.fire({
                                        title: "Disalin!",
                                        text: "Token berhasil disalin ke clipboard.",
                                        icon: "success",
                                        timer: 1500,
                                        showConfirmButton: false
                                    });
                                }).catch(function() {
                                    Swal.fire("Oops!", "Gagal menyalin token.",
                                        "error");
                                });
                            });



                        } else {
                            kuisContainer.append(
                                "<p class='text-muted'>Tidak ada kuis untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/kuis/show.blade.php ENDPATH**/ ?>