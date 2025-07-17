<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title><?php echo $__env->yieldContent('title'); ?> | Learning Manajement Syste</title>

    <?php echo $__env->make('includes.backend.css.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body>
    <div class="container-scroller">

        <?php echo $__env->make('layouts.navigation', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="container-fluid page-body-wrapper">

            <?php echo $__env->make('components.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

            <div class="main-panel">
                <div class="content-wrapper">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>

        <?php echo $__env->make('components.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </div>

    <?php echo $__env->make('includes.backend.js.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->yieldPushContent('scripts'); ?>


    <!-- Floating Chat Button -->
    <?php
        $showChatRoutes = [
            'submit-materi.show',
            'submit-kuis.show',
            'submit-tugas.show',
            'siswa-kelas.show',
            'absensi.show',
            'detail-absensi.index',
            'list-pertemuan-kuis.index',
            'hasil-kuis.show',
            'list-pertemuan-tugas.index',
        ];
    ?>

    <?php if(in_array(Route::currentRouteName(), $showChatRoutes)): ?>
        <!-- Floating Chat Button -->
        <a href="<?php echo e(route('forum-diskusi-guru.index', [
            'mapel' => Str::slug($pembelajaran->nama_mapel),
            'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
            'semester' => Str::slug($pembelajaran->semester),
        ])); ?>"
            class="chat-button" title="Forum Diskusi">
            <i class="fas fa-comments"></i>
        </a>
    <?php endif; ?>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/layouts/app.blade.php ENDPATH**/ ?>