<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Forum Diskusi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="shortcut icon" href="<?php echo e(url('storage/logo_sekolah/' . $profilSekolah->foto)); ?>" />

    <style>
        body {
            background-color: #e9f3ff;
        }

        .sidebar {
            background-color: #27548A;
            min-height: 100vh;
            color: white;
            padding: 2rem 1rem;
            position: relative;
        }

        .sidebar .btn {
            background-color: #fff;
            color: #1da1f2;
            border-radius: 20px;
            font-weight: bold;
        }

        .sidebar .nav-link {
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .sidebar .nav-link:hover,
        .sidebar .hover-effect:hover {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateX(5px);
        }


        .back-btn {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: white;
            text-decoration: none;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .card {
            border: none;
        }

        .post-card {
            background-color: #ffffff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .post-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(165, 45, 45, 0.1);
        }

        .right-panel {
            background-color: #27548A;
            padding: 2rem;
            border-left: 1px solid #dbeafe;
        }

        .follow-card {
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }

        .follow-card:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.08);
        }

        .follow-user {
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem 0;
        }

        .follow-user:last-child {
            border-bottom: none;
        }

        .follow-user img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
        }

        .follow-btn {
            background-color: white;
            color: #1da1f2;
            font-size: 0.8rem;
            padding: 3px 12px;
            border-radius: 20px;
            border: none;
        }

        .comment-count {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .right-panel img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
        }

        .active-sidebar {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff !important;
            border-radius: 8px;
            font-weight: bold;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-2 sidebar d-flex flex-column align-items-start">
                <!-- Back Button -->
                <a href="<?php echo e(route('mata-pelajaran.show', [
                    'mapel' => Str::slug($pembelajaran->nama_mapel),
                    'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                    'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                    'semester' => Str::slug($pembelajaran->semester),
                ])); ?>"
                    class="back-btn">
                    <i class="bi bi-arrow-left me-2"></i> Back
                </a>

                <!-- Header -->
                <div class="w-100 text-center mb-4 mt-5">
                    <h4 class="fw-bold">Forum Diskusi</h4>
                </div>

                <!-- Profile -->
                <?php
                    $user = Auth::user();
                ?>
                <div class="d-flex align-items-center bg-white text-dark p-2 rounded mb-4 w-100">
                    <img src="<?php echo e($user->foto ? asset('storage/foto_user' . $user->foto) : asset('assets/img/profil.png')); ?>"
                        class="profile-pic me-2" alt="<?php echo e($user->name); ?>">
                    <div class="overflow-hidden">
                        <div class="fw-semibold text-truncate" style="max-width: 200px;"><?php echo e($user->name); ?></div>
                        <small class="text-truncate d-block" style="max-width: 200px;"><?php echo e($user->email); ?></small>
                    </div>
                </div>

                <!-- Tambahan Konten Sidebar -->
                <div class="w-100 mb-4">
                    <?php
                        $isGuru = Auth::user()->hasRole('Guru');

                        $listRoute = $isGuru ? 'forum-diskusi-guru.index' : 'forum-diskusi.index';
                        $viewRoute = $isGuru ? 'forum-diskusi-guru.view' : 'forum-diskusi.view';

                        $listUrl = route($listRoute, [
                            'mapel' => Str::slug($pembelajaran->nama_mapel),
                            'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
                            'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
                            'semester' => Str::slug($pembelajaran->semester),
                        ]);

                        $isActive = url()->current() === $listUrl || request()->routeIs($viewRoute);
                    ?>

                    <ul class="nav flex-column w-100">
                        <li class="nav-item">
                            <a class="nav-link hover-effect <?php echo e($isActive ? 'active-sidebar' : 'text-white'); ?> text-center"
                                href="<?php echo e($listUrl); ?>">
                                <i class="bi bi-chat-dots-fill me-2"></i>Diskusi Saya
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Statistik Diskusi -->
                <div class="w-100 mb-4">
                    <h6 class="text-white fw-bold mb-3">Statistik</h6>
                    <div class="text-white small">
                        <p><i class="bi bi-chat-left-text me-2"></i>Total Diskusi:
                            <strong><?php echo e($pembelajaran->forum ? $pembelajaran->forum->count() : 0); ?></strong></p>
                        <p><i class="bi bi-people-fill me-2"></i>Anggota Aktif:
                            <strong><?php echo e(1 + ($pembelajaran->enrollments ? $pembelajaran->enrollments->count() : 0)); ?></strong>
                        </p>
                    </div>
                </div>

                <!-- Quotes Pendidikan -->
                <div class="w-100 bg-white text-dark rounded-3 p-3 shadow-sm mb-4">
                    <blockquote class="blockquote mb-0">
                        <p class="mb-1">"Pendidikan adalah senjata paling ampuh untuk mengubah dunia."</p>
                        <footer class="blockquote-footer text-end mt-1">Nelson Mandela</footer>
                    </blockquote>
                </div>


                <!-- Spacer -->
                <div class="flex-grow-1"></div>
            </aside>

            <!-- Main Content -->
            <main class="col-md-8 p-4">
                <div class="card p-4 shadow-sm">
                    <h4 class="mb-4">Forum Diskusi Kelas <?php echo e($pembelajaran->kelas->nama_kelas); ?> -
                        <?php echo e($pembelajaran->nama_mapel); ?></h4>
                    <?php echo $__env->yieldContent('content'); ?>

                </div>
            </main>

            <!-- Right Panel -->
            <aside class="col-md-2 right-panel">
                <div class="mb-4">
                    <input type="text" class="form-control form-control-sm rounded-pill"
                        placeholder="🔍 Cari pengguna">
                </div>

                <h6 class="fw-semibold mb-3 text-white">Semua Anggota</h6>

                
                <div class="bg-white text-dark rounded-3 p-1 mb-3 shadow-sm d-flex align-items-center follow-card">
                    <img src="<?php echo e($pembelajaran->guru->foto ? asset('storage/foto_user' . $pembelajaran->guru->foto) : asset('assets/img/profil.png')); ?>"
                        class="me-2" alt="guru">
                    <div class="overflow-hidden">
                        <div class="fw-bold text-truncate" style="max-width: 200px;"><?php echo e($pembelajaran->guru->name); ?>

                        </div>
                        <small class="text-muted">Guru</small>
                    </div>
                </div>

                
                <?php $__currentLoopData = $pembelajaran->enrollments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $enrollment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if($enrollment->siswa): ?>
                        <div
                            class="bg-white text-dark rounded-3 p-1 mb-3 shadow-sm d-flex align-items-center follow-card">
                            <img src="<?php echo e($enrollment->siswa->foto ? asset('storage/foto_user' . $enrollment->siswa->foto) : asset('assets/img/profil.png')); ?>"
                                class="me-2" alt="<?php echo e($enrollment->siswa->name); ?>">
                            <div class="overflow-hidden">
                                <div class="fw-bold text-truncate" style="max-width: 200px;">
                                    <?php echo e($enrollment->siswa->name); ?></div>
                                <small class="text-muted">Siswa</small>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            </aside>

        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Tambahkan SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if(session('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '<?php echo e(session('success')); ?>',
                showConfirmButton: false,
                timer: 2000
            });
        <?php endif; ?>
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.btn-delete-forum');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const forumId = this.getAttribute('data-id');
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Forum akan dihapus permanen!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById(`delete-form-${forumId}`).submit();
                        }
                    });
                });
            });
        });
    </script>

    <?php echo $__env->yieldPushContent('scripts'); ?>

</body>

</html>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/layouts/forum.blade.php ENDPATH**/ ?>