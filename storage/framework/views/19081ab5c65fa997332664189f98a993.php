<?php
    $user = Auth::user();

    // Guru
    $mataPelajaran =
        $user && $user->hasRole('Guru') ? \App\Models\Pembelajaran::where('guru_id', $user->id)->get() : collect();

    $currentRoute = Route::currentRouteName();

    $mataPelajaranAktif = $mataPelajaran->where('aktif', true);
    $mataPelajaranDraft = $mataPelajaran->where('aktif', false);

    // Walas
    $waliKelasList = \App\Models\WaliKelas::with(['kelas', 'tahunAjaran'])
        ->where('guru_id', $user->id)
        ->get();

    $waliKelasAktif = $waliKelasList->where('aktif', true);
    $waliKelasDraft = $waliKelasList->where('aktif', false);
?>


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        <?php if(session('active_role') === 'Wali Kelas'): ?>
            <?php
                $user = Auth::user();
            ?>

            
            <li class="nav-item px-3">
                <div class="card border-0 shadow-sm mb-3 rounded-xl text-center"
                    style="background: linear-gradient(135deg, #007bff, #6610f2);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center p-2">
                        <div class="font-weight-bold text-white mb-1" style="font-size: 1rem;">
                            <?php echo e($user->name); ?>

                        </div>
                        <div class="text-white small" style="font-size: 0.85rem;">
                            <?php echo e(ucfirst(str_replace('_', ' ', session('active_role')))); ?>

                        </div>
                    </div>
                </div>
            </li>
        <?php endif; ?>

        <li class="nav-items <?php echo e(Route::is('dashboard') ? 'active' : ''); ?>">
            <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                <div class="menu-item">
                    <i class="fas fa-tachometer-alt menu-icon mr-3"></i>
                    <span class="menu-title">Dashboard</span>
                </div>
            </a>
        </li>

        <?php if(hasPermissionForActiveRole('menu-data-materi') ||
                hasPermissionForActiveRole('menu-profile-sekolah') ||
                hasPermissionForActiveRole('menu-data-kurikulum') ||
                hasPermissionForActiveRole('menu-data-kelas') ||
                hasPermissionForActiveRole('menu-data-tahun-ajar') ||
                hasPermissionForActiveRole('menu-data-guru') ||
                hasPermissionForActiveRole('menu-data-walas') ||
                hasPermissionForActiveRole('menu-data-siswa') ||
                hasPermissionForActiveRole('menu-data-tugas') ||
                hasPermissionForActiveRole('menu-data-kuis') ||
                hasPermissionForActiveRole('menu-pembelajaran')): ?>
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Data</span>
            </li>
        <?php endif; ?>


        <?php if(hasPermissionForActiveRole('menu-profile-sekolah')): ?>
            <li class="nav-items <?php echo e(Route::is('profilesekolah.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('profilesekolah.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-school menu-icon mr-3"></i>
                        <span class="menu-title">Profile Sekolah</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(hasPermissionForActiveRole('menu-data-kurikulum')): ?>
            <li class="nav-items <?php echo e(Route::is('kurikulum.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('kurikulum.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-book menu-icon mr-3"></i>
                        <span class="menu-title">Kurikulum</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(hasPermissionForActiveRole('menu-data-kelas')): ?>
            <li class="nav-items <?php echo e(Route::is('kelas.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('kelas.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard menu-icon mr-3"></i>
                        <span class="menu-title">Data Kelas</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-tahun-ajar')): ?>
            <li class="nav-items <?php echo e(Route::is('tahun-ajaran.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('tahun-ajaran.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-calendar menu-icon mr-3"></i>
                        <span class="menu-title">Data Tahun Ajar</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-guru')): ?>
            <li class="nav-items <?php echo e(Route::is('guru.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('guru.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Data Guru</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-walas')): ?>
            <li class="nav-items <?php echo e(Route::is('wali-kelas.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('wali-kelas.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-users menu-icon mr-3"></i>
                        <span class="menu-title">Data Wali Kelas</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-siswa')): ?>
            <li class="nav-items <?php echo e(Route::is('siswa.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('siswa.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-user-graduate menu-icon mr-3"></i>
                        <span class="menu-title">Data Siswa</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-pembelajaran')): ?>
            <li class="nav-items <?php echo e(Route::is('pembelajaran.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('pembelajaran.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Pembelajaran</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(hasPermissionForActiveRole('menu-data-materi')): ?>
            <li class="nav-items <?php echo e(Route::is('materi.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('materi.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-book-open menu-icon mr-3"></i>
                        <span class="menu-title">Data Materi</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(hasPermissionForActiveRole('menu-data-tugas')): ?>
            <li class="nav-items <?php echo e(Route::is('tugas.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('tugas.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-tasks menu-icon mr-3"></i>
                        <span class="menu-title">Data Tugas</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-kuis')): ?>
            <li
                class="nav-items <?php echo e(request()->routeIs('kuis.index') || request()->routeIs('soal.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('kuis.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-question-circle menu-icon mr-3"></i>
                        <span class="menu-title">Data Kuis</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(session('active_role') === 'Guru'): ?>
            
            <?php if($mataPelajaranAktif->isNotEmpty()): ?>
                <li class="mt-3">
                    <span class="app-menu__item app-menu__label text-primary">Mata Pelajaran Aktif</span>
                </li>
            <?php endif; ?>

            <?php $__currentLoopData = $mataPelajaranAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo $__env->make('components.sidebar-mapel-item', ['mapel' => $mapel, 'isDraft' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if($mataPelajaranDraft->isNotEmpty()): ?>
                <li class="mt-3" x-data="{ openDraft: false }">
                    <div @click="openDraft = !openDraft"
                        class="app-menu__item app-menu__label text-secondary d-flex justify-content-between align-items-center cursor-pointer">
                        <span>Mata Pelajaran (Draft)</span>
                        <i :class="openDraft ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </div>

                    <ul x-show="openDraft" x-transition>
                        <?php $__currentLoopData = $mataPelajaranDraft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('components.sidebar-mapel-item', [
                                'mapel' => $mapel,
                                'isDraft' => true,
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            <?php endif; ?>
        <?php endif; ?>


        


        <?php if(hasPermissionForActiveRole('menu-data-operator') ||
                hasPermissionForActiveRole('menu-data-permission') ||
                hasPermissionForActiveRole('menu-data-role')): ?>
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Access</span>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-operator')): ?>
            <li class="nav-items <?php echo e(Route::is('users.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('users.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-user-shield menu-icon mr-3"></i>
                        <span class="menu-title">Data Operator</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-permission')): ?>
            <li class="nav-items <?php echo e(Route::is('permissions.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('permissions.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-key menu-icon mr-3"></i>
                        <span class="menu-title">Data Permission</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if(hasPermissionForActiveRole('menu-data-role')): ?>
            <li class="nav-items <?php echo e(Route::is('roles.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('roles.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-users-cog menu-icon mr-3"></i>
                        <span class="menu-title">Data Role</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>


        <?php if(session('active_role') === 'Wali Kelas'): ?>

            <?php if($waliKelasAktif->isNotEmpty()): ?>
                <li class="mt-2">
                    <span class="app-menu__item app-menu__label text-primary">Kelas Aktif</span>
                </li>

                <?php $__currentLoopData = $waliKelasAktif; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wali): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php echo $__env->make('components.sidebar-walas-item', ['wali' => $wali, 'isDraft' => false], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>

            <?php if($waliKelasDraft->isNotEmpty()): ?>
                <li class="mt-3" x-data="{ openDraftWalas: false }">
                    <div @click="openDraftWalas = !openDraftWalas"
                        class="app-menu__item app-menu__label text-secondary d-flex justify-content-between align-items-center cursor-pointer">
                        <span>Kelas (Draft)</span>
                        <i :class="openDraftWalas ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </div>

                    <ul x-show="openDraftWalas" x-transition>
                        <?php $__currentLoopData = $waliKelasDraft; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wali): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('components.sidebar-walas-item', ['wali' => $wali, 'isDraft' => true], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            <?php endif; ?>

        <?php endif; ?>


    </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/sidebar.blade.php ENDPATH**/ ?>