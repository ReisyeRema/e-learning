

<?php $__env->startSection('title', 'Data Pertemuan Materi'); ?>

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
                        <h4 class="mb-3">Daftar Materi Siswa <?php echo e($kelasData->nama_kelas); ?></h4>
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
                                        <h5 class="mb-3">Daftar Pertemuan Materi</h5>
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
                                    <h5 class="mb-3" id="judul-pertemuan">Klik Pertemuan untuk melihat materi yang
                                        diinputkan</h5>
                                    <div class="list-group" id="materi-container">
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
                    <h5 class="modal-title" id="addKurikulumModalLabel">Tambah Materi <?php echo e($kelasData->nama_kelas); ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="<?php echo e(route('pertemuan-materi.store', ['pembelajaran_id' => $pembelajaran->id])); ?>"
                    method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleSelectKelas">Pertemuan <span class="text-danger">*</span></label>
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
                            <label for="exampleSelectKelas">Materi <span class="text-danger">*</span></label>
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

    <!-- Modal Edit Materi -->
    <div class="modal fade" id="editMateriModal" tabindex="-1" aria-labelledby="editMateriModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <form id="editMateriForm" method="POST">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>
                    <div class="modal-header">
                        <h5 class="modal-title" id="editMateriModalLabel">Edit Materi</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="pembelajaran_id" value="<?php echo e($pembelajaran->id); ?>">
                        <input type="hidden" name="pertemuan_materi_id" id="editPertemuanMateriId">

                        <div class="form-group">
                            <label for="editPertemuanSelect">Pertemuan <span class="text-danger">*</span></label>
                            <select name="pertemuan_id" id="editPertemuanSelect" class="form-control">
                                <?php $__currentLoopData = $pertemuanSemua; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->judul); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="editMateriSelect">Materi <span class="text-danger">*</span></label>
                            <select name="materi_id" id="editMateriSelect" class="form-control">
                                <?php $__currentLoopData = $materi; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->judul); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
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

                // Hapus class 'active' dari semua item dan tambahkan ke yang diklik
                $(".pertemuan-item").removeClass("active");
                $(this).addClass("active");

                // Update judul pertemuan
                $("#judul-pertemuan").text(pertemuanJudul);

                $.ajax({
                    url: `/guru/materi/${pertemuanId}?pembelajaran_id=${pembelajaranId}`,
                    type: "GET",
                    success: function(response) {
                        let materiContainer = $("#materi-container");
                        materiContainer.empty();

                        if (response.length > 0) {
                            response.forEach(function(item) {
                                let fileUrl = item.materi.file_path ?
                                    `https://drive.google.com/file/d/${item.materi.file_path}/view` :
                                    "#";

                                let materiItem = `
                                    <div class="list-group-item d-flex justify-content-between align-items-center border rounded p-2 mb-2 materi-item"
                                        data-id="${item.id}" data-file-url="${fileUrl}">
                                        <div>
                                            <i class="fas fa-file-alt text-primary"></i>
                                            <span class="ml-2">${item.materi.judul || '-'}</span>
                                        </div>
                                        <div>
                                            <button class="btn btn-warning btn-sm edit-materi" 
                                                data-toggle="modal" 
                                                data-target="#editMateriModal" 
                                                data-id="${item.id}" 
                                                data-materi-id="${item.materi.id}" 
                                                data-pertemuan-id="${item.pertemuan_id}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-danger btn-sm delete-materi" data-id="${item.id}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>`;


                                materiContainer.append(materiItem);
                            });

                            // Gunakan delegation agar klik ke .materi-item bisa dibatalkan dari child-nya
                            $(document).on("click", ".materi-item", function(e) {
                                // Kalau yang diklik adalah tombol edit atau delete, jangan buka file
                                if ($(e.target).closest(".edit-materi, .delete-materi")
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



                            // Tampilkan data ke dalam modal edit saat tombol edit diklik
                            $(document).on("click", ".edit-materi", function(e) {
                                e
                                    .stopPropagation(); // Penting: mencegah klik masuk ke materi-item dan membuka file

                                let id = $(this).data("id");
                                let materiId = $(this).data("materi-id");
                                let pertemuanId = $(this).data("pertemuan-id");

                                $("#editPertemuanMateriId").val(id);
                                $("#editPertemuanSelect").val(pertemuanId);
                                $("#editMateriSelect").val(materiId);

                                let actionUrl = `/guru/pertemuan-materi/${id}`;
                                $("#editMateriForm").attr("action", actionUrl);
                            });



                            // Event klik untuk hapus materi dengan SweetAlert
                            $(".delete-materi").click(function(e) {
                                e.preventDefault();
                                e
                                    .stopPropagation(); // Mencegah event bubbling ke elemen .materi-item

                                let materiId = $(this).data("id");
                                let parentItem = $(this).closest(".materi-item");

                                Swal.fire({
                                    title: "Apakah Anda yakin?",
                                    text: "Materi ini akan dihapus secara permanen!",
                                    icon: "warning",
                                    showCancelButton: true,
                                    confirmButtonColor: "#d33",
                                    cancelButtonColor: "#3085d6",
                                    confirmButtonText: "Ya, hapus!",
                                    cancelButtonText: "Batal"
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $.ajax({
                                            url: "/guru/pertemuan-materi/" +
                                                materiId,
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
                                                        "Materi telah dihapus.",
                                                        "success"
                                                    );
                                                } else {
                                                    Swal.fire(
                                                        "Gagal!",
                                                        "Gagal menghapus materi.",
                                                        "error"
                                                    );
                                                }
                                            },
                                            error: function() {
                                                Swal.fire(
                                                    "Error!",
                                                    "Terjadi kesalahan saat menghapus materi.",
                                                    "error"
                                                );
                                            }
                                        });
                                    }
                                });
                            });


                        } else {
                            materiContainer.append(
                                "<p class='text-muted'>Tidak ada materi untuk pertemuan ini.</p>"
                            );
                        }
                    }
                });
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/admin/materi/show.blade.php ENDPATH**/ ?>