<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <?php echo $__env->make('includes.frontend.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.css-main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</head>

<body>
    <?php echo $__env->make('components.frontend.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <div class="overlay" id="overlay"></div>

    <main class="main">
        <?php echo $__env->make('components.frontend.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <section class="content">
            <!-- Profile Section -->
            <div class="d-flex justify-content-between align-items-center pb-3 mb-3">
                <div class="d-flex align-items-center">
                    <img src="<?php echo e(Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png')); ?>"
                        alt="Profile Picture" class="rounded-circle me-3" width="60" height="60">
                    <div class="text">
                        <p class="mb-1 text-muted">Selamat Datang,</p>
                        <h5 class="mb-0 fw-bold"><?php echo e(Auth::user()->name); ?></h5>
                    </div>
                </div>

                <div class="d-flex gap-2">
                    <a href="<?php echo e(route('profile-siswa.edit')); ?>"
                       class="btn btn-outline-success d-flex align-items-center gap-1">
                        <i class="bi bi-person-lines-fill"></i> Data Diri
                    </a>
                
                    <a href="<?php echo e(route('setifikat-siswa.index')); ?>"
                       class="btn btn-outline-primary d-flex align-items-center gap-1">
                        <i class="bi bi-person-lines-fill"></i> Sertifikat
                    </a>
                </div>
                
            </div>


            <!-- Garis Abu-abu -->
            <hr class="profile-divider-2 mb-5">

            <?php echo $__env->yieldContent('content'); ?>

        </section>
    </main>

    <?php echo $__env->make('components.frontend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/layouts/main.blade.php ENDPATH**/ ?>