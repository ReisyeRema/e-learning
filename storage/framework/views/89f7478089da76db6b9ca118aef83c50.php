<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelajaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <?php echo $__env->make('includes.frontend.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.style-kelas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <style>
        .materi-section {
            background-color: #f0f7ff;
            padding: 10px;
            font-weight: bold;
            border-radius: 5px;
        }

        .materi-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 15px;
            border-bottom: 1px solid #ddd;
        }

        .materi-item:last-child {
            border-bottom: none;
        }

        .toggle-btn {
            cursor: pointer;
            margin-right: 10px;
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            border: none;
        }

        .modal-header {
            background-color: #ededed;
            color: white;
            border-radius: 10px 10px 0 0;
        }

        .modal-body {
            padding: 20px;
            background-color: #f8f9fa;
        }

        .modal-footer {
            background-color: #f1f1f1;
            border-radius: 0 0 10px 10px;
        }

        .modal-footer .btn {
            border-radius: 5px;
        }

        .modal-footer .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .modal-footer .btn-primary:hover {
            background-color: #0056b3;
        }

        .form-control {
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        .btn-close {
            background-color: white;
            border-radius: 50%;
            padding: 5px;
        }

        .upload-box {
            border: 2px dashed #007bff;
            border-radius: 5px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
            position: relative;
            background-color: #fdf9f9;
        }

        .upload-area {
            color: #333;
            font-size: 16px;
        }

        .upload-area i {
            font-size: 24px;
            color: #007bff;
            display: block;
            margin-bottom: 10px;
        }

        .browse-text {
            color: #007bff;
            font-weight: bold;
            cursor: pointer;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            left: 0;
            top: 0;
        }
    </style>

</head>

<body>
    <?php echo $__env->make('components.frontend.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="overlay" id="overlay"></div>

    <main class="main p-3">
        <div class="container">
            <!-- Tombol Kembali -->
            <div class="mb-3">
                <a href="<?php echo e(route('mata-pelajaran.index')); ?>" class="btn btn-light">
                    <i class="fas fa-arrow-left"></i> Kembali ke Halaman Mata Pelajaran
                </a>
            </div>

            <div class="card shadow-sm border-20 p-3">
                <h5 class="fw-bold"><?php echo e($pembelajaran->nama_mapel); ?> - <?php echo e($pembelajaran->kelas->nama_kelas); ?></h5>

                <?php
                    $groupedData = [];

                    // Mengelompokkan Materi
                    foreach ($pembelajaran->pertemuanMateri as $materi) {
                        $groupedData[$materi->materi->judul]['materi'][] = [
                            'judul' => $materi->materi->judul,
                            'file_path' => $materi->materi->file_path ?? null,
                        ];
                    }

                    // Mengelompokkan Tugas berdasarkan Materi
                    foreach ($pembelajaran->pertemuanTugas as $tugas) {
                        $judulMateri = $tugas->tugas->materi->judul ?? 'Materi Tidak Diketahui';
                        $groupedData[$judulMateri]['tugas'][] = [
                            // 'pertemuan_ke' => $tugas->pertemuan_ke,
                            'id' => $tugas->tugas->id,
                            'pertemuan_ke' => $tugas->pertemuan->judul,
                            'judul' => $tugas->tugas->judul,
                            'file_path' => $tugas->tugas->file_path,
                            'deadline' => $tugas->deadline,
                            'status' =>
                                optional($tugas->tugas->submitTugas->where('siswa_id', auth()->id())->last())->status ??
                                'Belum Dikumpulkan',
                        ];
                    }

                    // Mengelompokkan Kuis berdasarkan Materi
                    foreach ($pembelajaran->pertemuanKuis as $kuis) {
                        $kategori = $kuis->kuis->kategori;

                        if (in_array($kategori, ['Ujian Mid', 'Ujian Akhir'])) {
                            $judulGroup = $kategori; // Jadi "Ujian Mid" atau "Ujian Akhir"
                        } else {
                            $judulGroup = $kuis->kuis->materi->judul ?? 'Materi Tidak Diketahui';
                        }

                        $groupedData[$judulGroup]['kuis'][] = [
                            'id_pertemuan' => $kuis->id,
                            'id' => $kuis->kuis->id,
                            'pertemuan_ke' => $kuis->pertemuan->judul,
                            'judul' => $kuis->kuis->judul,
                            'nama_mapel' => $pembelajaran->nama_mapel,
                            'nama_kelas' => $pembelajaran->kelas->nama_kelas,
                            'tahun_ajaran' => $pembelajaran->tahunAjaran->nama_tahun,
                            'kategori_kuis' => $kategori,
                        ];
                    }
                ?>

                <?php if(empty($groupedData)): ?>
                    <p class="text-muted">Belum ada materi, tugas, atau kuis.</p>
                <?php else: ?>
                    <?php $__currentLoopData = $groupedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $judul => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="materi-section"><?php echo e($judul); ?></div>
                        <div>
                            <?php if(!empty($data['materi'])): ?>
                                <?php $__currentLoopData = $data['materi']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $materi): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="materi-item">
                                        <span><i class="fas fa-book text-primary me-3"></i>
                                            <?php echo e($materi['judul']); ?></span>
                                        <?php if(!empty($materi['file_path'])): ?>
                                            <a href="https://drive.google.com/file/d/<?php echo e($materi['file_path']); ?>/view"
                                                target="_blank" class="btn btn-outline-success btn-sm">
                                                Lihat Materi
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>


                            <?php if(!empty($data['tugas'])): ?>
                                <?php $__currentLoopData = $data['tugas']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tugas): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="materi-item">
                                        <span><i class="fas fa-file-alt text-warning me-3"></i> Tugas
                                            <?php echo e($tugas['pertemuan_ke']); ?>

                                            - <?php echo e($tugas['judul']); ?></span>
                                        <button class="btn btn-outline-warning btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#modalKumpul" data-tugas-judul="<?php echo e($tugas['judul']); ?>"
                                            data-tugas-id="<?php echo e($tugas['id']); ?>"
                                            data-tugas-deadline="<?php echo e($tugas['deadline'] ?? 'Tidak ada deadline'); ?>"
                                            data-tugas-link="https://drive.google.com/file/d/<?php echo e($tugas['file_path'] ?? ''); ?>/view"
                                            data-tugas-status="<?php echo e($tugas['status']); ?>">
                                            Kumpul
                                        </button>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <?php if(!empty($data['kuis'])): ?>
                                <?php $__currentLoopData = $data['kuis']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kuis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="materi-item">
                                        <span><i class="fas fa-question-circle text-info me-3"></i>
                                            <?php echo e($kuis['pertemuan_ke']); ?> - <?php echo e($kuis['kategori_kuis']); ?> -
                                            <?php echo e($kuis['judul']); ?></span>

                                        <!-- Tombol Kerjakan Kuis -->
                                        <a href="#" class="btn btn-outline-primary btn-sm btn-kerjakan-kuis"
                                            data-link="<?php echo e(route('kuis-siswa.action', [
                                                'mapel' => Str::slug($kuis['nama_mapel']),
                                                'kelas' => Str::slug($kuis['nama_kelas']),
                                                'tahunAjaran' => str_replace('/', '-', $kuis['tahun_ajaran']),
                                                'judulKuis' => Str::slug($kuis['judul']),
                                            ])); ?>"
                                            data-pertemuan-id="<?php echo e($kuis['id_pertemuan']); ?>">
                                            Kerjakan
                                        </a>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php endif; ?>

            </div>
        </div>

        

        <!-- Modal Kumpul Tugas -->
        <div class="modal fade" id="modalKumpul" tabindex="-1" aria-labelledby="modalKumpulLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalKumpulLabel" style="color: #000000">Kumpulkan Tugas</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="<?php echo e(route('submit-tugas-siswa.store')); ?>"
                            enctype="multipart/form-data">

                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="tugas_id" id="tugasId" value="">
                            <input type="hidden" name="siswa_id" value="<?php echo e(auth()->id()); ?>">

                            <div class="mb-3" style="display: table;">
                                <div style="display: table-row;">
                                    <strong style="display: table-cell; padding-right: 10px;">Status
                                        Pengumpulan</strong>
                                    <span style="display: table-cell;">
                                        : <span id="statusPengumpulan" class="badge bg-secondary">Belum
                                            dikumpulkan</span>
                                    </span>
                                </div>
                                <div style="display: table-row;">
                                    <strong style="display: table-cell; padding-right: 10px;">Detail Tugas</strong>
                                    <span style="display: table-cell;">: <a id="tugasLink" href="#"
                                            target="_blank">Lihat Tugas</a></span>
                                </div>
                                <div style="display: table-row;">
                                    <strong style="display: table-cell; padding-right: 10px;">Deadline</strong>
                                    <span style="display: table-cell;">: <span id="tugasDeadline"></span></span>
                                </div>
                                <div style="display: table-row;">
                                    <strong style="display: table-cell; padding-right: 10px;">Judul Tugas</strong>
                                    <span style="display: table-cell;">: <span id="tugasJudul"></span></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="fileTugas" class="form-label">Upload Tugas</label>
                                <div class="upload-box">
                                    <input type="file" id="fileTugas" name="file_path" class="file-input">
                                    <div class="upload-area">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Drag your file(s) or <span class="browse-text">browse</span></p>
                                        <small>Max 10 MB files are allowed</small>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="urlTugas" class="form-label">URL (Opsional)</label>
                                <input type="text" class="form-control" name="url" id="urlTugas">
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Batal</button>
                                
                                <button type="submit" class="btn btn-success" id="submitButton">Kumpulkan</button>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Input Token -->
        <div class="modal fade" id="modalTokenKuis" tabindex="-1" aria-labelledby="modalTokenLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <form method="POST" action="<?php echo e(route('kuis-siswa.cek-token')); ?>" id="formTokenKuis">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="pertemuan_kuis_id" id="pertemuan_kuis_id">
                    <input type="hidden" name="redirect_link" id="redirect_link">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Masukkan Token Kuis</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Tutup"></button>
                        </div>
                        <div class="modal-body">
                            <input type="text" name="token" class="form-control" placeholder="Masukkan token" required
                                <?php echo e(session('token_locked') ? 'disabled' : ''); ?>>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" <?php echo e(session('token_locked') ? 'disabled' : ''); ?>>
                                Mulai Kuis
                            </button>                        </div>
                    </div>
                </form>
            </div>
        </div>


    </main>
    <!-- Tambahkan ini sebelum </body> -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let modalKumpul = document.getElementById("modalKumpul");

            modalKumpul.addEventListener("show.bs.modal", function(event) {
                let button = event.relatedTarget; // Tombol yang diklik untuk membuka modal

                // Ambil data dari atribut tombol
                let tugasJudul = button.getAttribute("data-tugas-judul") || "Tidak ada judul";
                let tugasLink = button.getAttribute("data-tugas-link") || "#";
                let tugasId = button.getAttribute("data-tugas-id") || "";
                let tugasDeadline = button.getAttribute("data-tugas-deadline") || "Tidak ada deadline";
                let statusPengumpulan = button.getAttribute("data-tugas-status") || "belum";

                console.log("Tugas ID yang diklik:", tugasId);

                // Konversi tanggal deadline
                let deadlineDate = new Date(tugasDeadline);
                let now = new Date();

                // Masukkan data ke dalam modal
                document.getElementById("tugasJudul").textContent = tugasJudul;
                document.getElementById("tugasLink").href = tugasLink;
                document.getElementById("tugasId").value = tugasId;
                document.getElementById("tugasDeadline").textContent = tugasDeadline;

                let statusElement = document.getElementById("statusPengumpulan");
                let submitButton = document.getElementById("submitButton");

                // Periksa apakah tugas dikumpulkan setelah deadline
                if (statusPengumpulan === "sudah_dikumpulkan") {
                    if (now > deadlineDate) {
                        statusElement.classList.remove("bg-secondary", "bg-success");
                        statusElement.classList.add("bg-warning");
                        statusElement.textContent = "Sudah Dikumpulkan (Terlambat)";
                    } else {
                        statusElement.classList.remove("bg-secondary", "bg-danger");
                        statusElement.classList.add("bg-success");
                        statusElement.textContent = "Sudah Dikumpulkan";
                    }
                    // Ubah tombol menjadi "Hapus"
                    submitButton.classList.remove("btn-success");
                    submitButton.classList.add("btn-danger");
                    submitButton.textContent = "Hapus";
                    submitButton.setAttribute("type", "button");
                    submitButton.setAttribute("onclick", `hapusTugas(${tugasId})`);
                } else {
                    if (now > deadlineDate) {
                        statusElement.classList.remove("bg-secondary", "bg-success");
                        statusElement.classList.add("bg-secondary");
                        statusElement.textContent = "Belum Dikumpulkan (Terlambat)";
                        // Ubah tombol menjadi "Kumpulkan"
                        submitButton.classList.remove("btn-danger");
                        submitButton.classList.add("btn-success");
                        submitButton.textContent = "Kumpulkan";
                        submitButton.setAttribute("type", "submit");
                        submitButton.removeAttribute("onclick");
                    } else {
                        statusElement.classList.remove("bg-secondary", "bg-danger");
                        statusElement.classList.add("bg-secondary");
                        statusElement.textContent = "Belum Dikumpulkan";
                        // Ubah tombol menjadi "Kumpulkan"
                        submitButton.classList.remove("btn-danger");
                        submitButton.classList.add("btn-success");
                        submitButton.textContent = "Kumpulkan";
                        submitButton.setAttribute("type", "submit");
                        submitButton.removeAttribute("onclick");
                    }
                }
            });
        });

        function hapusTugas(tugasId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Tugas yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = document.createElement("form");
                    form.method = "POST";
                    form.action = `/siswa/hapus-tugas-siswa/${tugasId}`;

                    let csrfInput = document.createElement("input");
                    csrfInput.type = "hidden";
                    csrfInput.name = "_token";
                    csrfInput.value = "<?php echo e(csrf_token()); ?>";

                    let methodInput = document.createElement("input");
                    methodInput.type = "hidden";
                    methodInput.name = "_method";
                    methodInput.value = "DELETE";

                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

    <script>
        document.querySelector(".file-input").addEventListener("change", function(e) {
            let fileName = e.target.files.length > 0 ? e.target.files[0].name : "Drag your file(s) or browse";
            document.querySelector(".upload-area p").innerHTML = fileName;
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if(session('success')): ?>
                Swal.fire({
                    title: "Berhasil!",
                    text: "<?php echo e(session('success')); ?>",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            <?php endif; ?>
        });
    </script>

    

    <script>
        document.querySelectorAll('.btn-kerjakan-kuis').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const pertemuanId = this.dataset.pertemuanId;
                const link = this.dataset.link;

                document.getElementById('pertemuan_kuis_id').value = pertemuanId;
                document.getElementById('redirect_link').value = link;

                $('#modalTokenKuis').modal('show');
            });
        });
    </script>

    <?php if(session('token_error')): ?>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Token Salah',
                text: '<?php echo e(session('token_error')); ?>',
            });
        </script>
    <?php endif; ?>

    
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            <?php if(session('token_locked')): ?>
                const modal = new bootstrap.Modal(document.getElementById('modalTokenKuis'));
                modal.show();
            <?php endif; ?>
        });
    </script>
    


</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/mataPelajaran/show.blade.php ENDPATH**/ ?>