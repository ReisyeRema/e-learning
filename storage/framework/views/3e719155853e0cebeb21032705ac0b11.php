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

</head>

<body>
    <?php echo $__env->make('components.frontend.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="overlay" id="overlay"></div>

    <main class="main py-5">
        <div class="container">
            <h2 class="mb-5 fw-bold text-center">Mata Pelajaran - <?php echo e($kelas->nama_kelas); ?></h2>
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
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        <?php $__empty_1 = true; $__currentLoopData = $pembelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pelajaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0">
                                    <?php if($pelajaran->cover): ?>
                                        <img src="<?php echo e(asset('storage/covers/' . $pelajaran->cover)); ?>"
                                            alt="Cover <?php echo e($pelajaran->nama_mapel); ?>" class="card-img-top"
                                            style="height: 180px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('assets/img/e-learning.png')); ?>" alt="Default Cover"
                                            class="card-img-top" style="height: 180px; object-fit: cover;">
                                    <?php endif; ?>

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo e($pelajaran->nama_mapel); ?></h5>
                                        <p class="mb-1 text-muted"><i class="fas fa-calendar-alt me-1"></i> Tahun
                                            Ajaran: <strong><?php echo e($pelajaran->tahunAjaran->nama_tahun ?? '-'); ?></strong>
                                        </p>
                                        <p class="mb-2 text-muted"><i class="fas fa-chalkboard-teacher me-1"></i>
                                            <?php echo e($pelajaran->guru->name ?? 'Tidak ada data'); ?></p>

                                        <div class="mt-auto">
                                            <?php
                                                $isEnrolled = isset($enrollments[$pelajaran->id]);
                                                $isPending =
                                                    $isEnrolled && $enrollments[$pelajaran->id]->status === 'pending';
                                                $isApproved =
                                                    $isEnrolled && $enrollments[$pelajaran->id]->status === 'approved';
                                            ?>

                                            <?php if(!$isEnrolled): ?>
                                                <button class="btn btn-primary w-100"
                                                    onclick="enrollSiswa(<?php echo e($pelajaran->id); ?>)">Daftar</button>
                                            <?php elseif($isPending): ?>
                                                <span
                                                    class="bg-warning text-dark d-inline-block px-3 py-1 mt-2 rounded small">Menunggu
                                                    persetujuan guru</span>
                                            <?php elseif($isApproved): ?>
                                                <span
                                                    class="bg-success text-white d-inline-block px-3 py-1 mt-2 rounded small">Anda
                                                    sudah terdaftar</span>
                                            <?php endif; ?>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-12">
                                <div class="alert alert-warning text-center">Tidak ada mata pelajaran untuk tahun ajaran
                                    ini.</div>
                            </div>
                        <?php endif; ?>
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