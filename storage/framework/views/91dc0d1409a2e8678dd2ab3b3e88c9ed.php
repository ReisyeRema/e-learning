<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
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
            <div class="profile-header">
                <img src="<?php echo e(Auth::user()->foto ? asset('storage/foto_user/' . Auth::user()->foto) : asset('assets/img/profil.png')); ?>" alt="Profile Picture">
                <div class="text">
                    <p>Selamat Datang,</p>
                    <h2><?php echo e(Auth::user()->name); ?></h2>
                </div>
            </div>

            <!-- Garis Abu-abu -->
            <hr class="profile-divider mb-5">

            <?php echo $__env->yieldContent('content'); ?>
            
        </section>
    </main>
    
    <?php echo $__env->make('components.frontend.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('includes.frontend.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/layouts/main.blade.php ENDPATH**/ ?>