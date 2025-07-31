<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelajaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <?php echo $__env->make('includes.frontend.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.style-kelas', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <style>
        .cover-image {
            max-height: 140px;
            object-fit: contain;
            padding: 10px;
            background-color: #fff;
            border-top-left-radius: .5rem;
            border-top-right-radius: .5rem;
        }
    </style>
</head>

<body>
    <?php echo $__env->make('components.frontend.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="overlay" id="overlay"></div>

    <main class="main py-5">
        <div class="container">
            <!-- Judul Halaman -->
            <div class="text-center mb-4">
                <h1 class="fw-bold display-5">Pendaftaran Mata Pelajaran</h1>
                <p class="lead text-muted mt-3 mb-4 px-md-5">
                    Silakan memilih mata pelajaran yang tersedia sesuai dengan jurusan dan kelas Anda.
                    Pastikan Anda mendaftar pada mata pelajaran yang akan diikuti selama tahun ajaran ini.
                    Proses pembelajaran akan dibimbing oleh tenaga pendidik yang kompeten di bidangnya.
                </p>
            </div>

            <!-- Subjudul & Border Bawah -->
            <div
                class="d-flex justify-content-between align-items-center bg-white px-4 py-3 rounded-top shadow-sm border border-bottom-0 mb-0">
                <h2 class="fs-5 fw-bold mb-0">
                    <i class="fas fa-book-open me-2"></i> Daftar Mata Pelajaran -
                    <span class="text-success"><?php echo e($kelas->nama_kelas); ?></span>
                </h2>
                
            </div>


            <div class="border-top-0 border shadow-sm rounded-bottom bg-white p-4">
                <div class="row">
                    
                    <div class="col-md-3 mb-4">
                        <div class="bg-sidebar rounded shadow-sm p-3">
                            <h5 class="mb-3">Filter Tahun Ajaran</h5>
                            <div class="list-group">
                                <?php $__currentLoopData = $tahunAjaranList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tahun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <a href="<?php echo e(route('kelas.show', ['id' => $kelas->id, 'tahun_ajaran_id' => $tahun->id])); ?>"
                                        class="list-group-item list-group-item-action <?php echo e($tahunAjaranDipilih == $tahun->id ? 'active' : ''); ?>">
                                        <?php echo e($tahun->nama_tahun); ?>

                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>

                    
                    <div class="col-md-9">
                        <div class="row">
                            

                            <?php $__empty_1 = true; $__currentLoopData = $pembelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pelajaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <div class="col-12">
                                    <div class="card mb-2 shadow-sm border border-secondary-subtle rounded-3 overflow-hidden p-3"
                                        style="min-height: 125px;">
                                        <div class="row g-0 align-items-center">
                                            <!-- Cover Gambar -->
                                            <div class="col-auto">
                                                <?php if($pelajaran->cover): ?>
                                                    <img src="<?php echo e(asset('storage/covers/' . $pelajaran->cover)); ?>"
                                                        alt="Cover <?php echo e($pelajaran->nama_mapel); ?>" class="img-fluid"
                                                        style="width: 200px; height: 100%; object-fit: cover;">
                                                <?php else: ?>
                                                    <img src="<?php echo e(asset('assets/img/default-cover.png')); ?>"
                                                        alt="Default Cover" class="img-fluid"
                                                        style="width: 200px; height: 100%; object-fit: cover;">
                                                <?php endif; ?>
                                            </div>

                                            <!-- Konten -->
                                            <div class="col ps-4 pe-4 py-3">
                                                <div class="d-flex justify-content-between flex-wrap align-items-start">
                                                    <h3 class="text-success fw-semibold mb-1">
                                                        <?php echo e($pelajaran->nama_mapel); ?>

                                                    </h3>
                                                    <span
                                                        class="badge bg-success text-white mt-1"><?php echo e($pelajaran->tahunAjaran->nama_tahun ?? '-'); ?></span>
                                                </div>

                                                <p class="text-muted small mb-2">
                                                    <i class="fas fa-chalkboard-teacher me-1"></i>
                                                    <?php echo e($pelajaran->guru->name ?? 'Tidak ada data'); ?>

                                                </p>

                                                
                                                <?php
                                                    $isEnrolled = isset($enrollments[$pelajaran->id]);
                                                    $isPending =
                                                        $isEnrolled &&
                                                        $enrollments[$pelajaran->id]->status === 'pending';
                                                    $isApproved =
                                                        $isEnrolled &&
                                                        $enrollments[$pelajaran->id]->status === 'approved';
                                                ?>

                                                <div class="mt-2">
                                                    <?php if(!$isEnrolled): ?>
                                                        <button class="btn btn-primary btn-sm"
                                                            onclick="enrollSiswa(<?php echo e($pelajaran->id); ?>)">
                                                            <i class="fas fa-sign-in-alt me-1"></i> Daftar
                                                        </button>
                                                    <?php elseif($isPending): ?>
                                                        <span
                                                            class="badge bg-warning text-dark px-3 py-2 small rounded">
                                                            <i class="fas fa-clock me-1"></i> Menunggu persetujuan
                                                        </span>
                                                    <?php elseif($isApproved): ?>
                                                        <span
                                                            class="badge bg-success text-white px-3 py-2 small rounded">
                                                            <i class="fas fa-check-circle me-1"></i> Anda sudah
                                                            terdaftar
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <div class="col-12">
                                    <div class="alert alert-warning text-center">
                                        Tidak ada mata pelajaran untuk tahun ajaran ini.
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>


    <?php echo $__env->make('components.frontend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script>
        function enrollSiswa(pembelajaranId) {
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: "Apakah Anda yakin ingin mendaftar ke mata pelajaran ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("<?php echo e(route('enroll.store')); ?>", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                            },
                            body: JSON.stringify({
                                pembelajaran_id: pembelajaranId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#198754'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mendaftar. Silakan coba lagi.',
                                confirmButtonColor: '#d33'
                            });
                        });
                }
            });
        }
    </script>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/pages/siswa/kelas/show.blade.php ENDPATH**/ ?>