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

    <main class="main p-5">
        <div class="container">
            <h2>Mata Pelajaran - <?php echo e($kelas->nama_kelas); ?></h2>
            <div class="row g-4">
                <?php $__currentLoopData = $pembelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pelajaran): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            <?php if($pelajaran->cover): ?>
                                <img src="<?php echo e(asset('storage/covers/' . $pelajaran->cover)); ?>"
                                    alt="Cover <?php echo e($pelajaran->nama_mapel); ?>" class="card-cover">
                            <?php else: ?>
                                <img src="<?php echo e(asset('assets/img/e-learning.png')); ?>" alt="Default Cover" height="300px">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($pelajaran->nama_mapel); ?></h5>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran:
                                    <strong><?php echo e($pelajaran->tahunAjaran->nama_tahun ?? 'Tidak ada data'); ?></strong>
                                </p>
                                <p>
                                    <span class="badge-guru">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        <?php echo e($pelajaran->guru->name ?? 'Tidak ada data'); ?>

                                    </span>
                                </p>

                                <?php
                                $isEnrolled = isset($enrollments[$pelajaran->id]);
                                $isPending = $isEnrolled && $enrollments[$pelajaran->id]->status === 'pending';
                                $isApproved = $isEnrolled && $enrollments[$pelajaran->id]->status === 'approved';
                                ?>

                                <?php if(!$isEnrolled): ?>
                                    <button class="btn-daftar"
                                        onclick="enrollSiswa(<?php echo e($pelajaran->id); ?>)">Daftar</button>
                                <?php elseif($isPending): ?>
                                    <span class="text-warning">Menunggu persetujuan guru</span>
                                <?php elseif($isApproved): ?>
                                    <span class="text-success">Anda sudah terdaftar</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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