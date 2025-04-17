<?php
    $user = Auth::user();
    $mataPelajaran =
        $user && $user->hasRole('Guru') ? \App\Models\Pembelajaran::where('guru_id', $user->id)->get() : collect();

    $currentRoute = Route::currentRouteName();
?>


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-items <?php echo e(Route::is('dashboard') ? 'active' : ''); ?>">
            <a class="nav-link" href="<?php echo e(route('dashboard')); ?>">
                <div class="menu-item">
                    <i class="fas fa-tachometer-alt menu-icon mr-3"></i>
                    <span class="menu-title">Dashboard</span>
                </div>
            </a>
        </li>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-profile-sekolah')): ?>
            <li class="nav-items <?php echo e(Route::is('profilesekolah.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('profilesekolah.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-school menu-icon mr-3"></i>
                        <span class="menu-title">Profile Sekolah</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-kurikulum')): ?>
            <li class="nav-items <?php echo e(Route::is('kurikulum.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('kurikulum.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-book menu-icon mr-3"></i>
                        <span class="menu-title">Kurikulum</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (\Illuminate\Support\Facades\Blade::check('hasanyrole', 'Guru|Admin')): ?>
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Data</span>
            </li>
        <?php endif; ?>


        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-kelas')): ?>
            <li class="nav-items <?php echo e(Route::is('kelas.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('kelas.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard menu-icon mr-3"></i>
                        <span class="menu-title">Data Kelas</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-tahun-ajar')): ?>
            <li class="nav-items <?php echo e(Route::is('tahun-ajaran.index') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('tahun-ajaran.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-calendar menu-icon mr-3"></i>
                        <span class="menu-title">Data Tahun Ajar</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-guru')): ?>
            <li class="nav-items <?php echo e(Route::is('guru.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('guru.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Data Guru</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-siswa')): ?>
            <li class="nav-items <?php echo e(Route::is('siswa.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('siswa.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-user-graduate menu-icon mr-3"></i>
                        <span class="menu-title">Data Siswa</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-pembelajaran')): ?>
            <li class="nav-items <?php echo e(Route::is('pembelajaran.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('pembelajaran.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Pembelajaran</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-materi')): ?>
            <li class="nav-items <?php echo e(Route::is('materi.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('materi.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-book-open menu-icon mr-3"></i>
                        <span class="menu-title">Data Materi</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-tugas')): ?>
            <li class="nav-items <?php echo e(Route::is('tugas.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('tugas.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-tasks menu-icon mr-3"></i>
                        <span class="menu-title">Data Tugas</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-kuis')): ?>
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

        


        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Guru')): ?>
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Mata Pelajaran</span>
            </li>
        <?php endif; ?>


        

        <?php $__currentLoopData = $mataPelajaran; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mapel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
                $slugMapel = Str::slug($mapel->nama_mapel);
                $slugKelas = Str::slug(optional($mapel->kelas)->nama_kelas ?? '');
                $tahunAjaranRaw = optional($mapel->tahunAjaran)->nama_tahun ?? '';
                $slugTahunAjaran = $tahunAjaranRaw ? str_replace('/', '-', $tahunAjaranRaw) : '';

                // Ambil full path saat ini (tanpa domain)
                $currentPath = request()->path(); // contoh: 'guru/submit-materi/ipa-7/a/2023-2024'

                // Buat pattern pencarian yang fleksibel (support prefix)
                $activePatterns = [
                    "*submit-materi/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "*submit-tugas/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "*submit-kuis/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "*siswa-kelas/$slugMapel/$slugKelas/$slugTahunAjaran",

                    // Tambahan route baru:
                    "*submit-tugas/$slugMapel/$slugKelas/$slugTahunAjaran/list-tugas",
                    "*submit-kuis/$slugMapel/$slugKelas/$slugTahunAjaran/list-kuis",
                    "*pertemuan-kuis/$slugMapel/$slugKelas/$slugTahunAjaran/hasil-kuis/*/*",
                ];

                $isActive = false;
                foreach ($activePatterns as $pattern) {
                    if (Str::is($pattern, $currentPath)) {
                        $isActive = true;
                        break;
                    }
                }
            ?>

            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-submit-materi')): ?>
                <li class="nav-items <?php echo e($isActive ? 'active' : ''); ?>">
                    <a class="nav-link"
                        href="<?php echo e(route('submit-materi.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran])); ?>">
                        <div class="menu-item">
                            <i class="fas fa-clipboard-check menu-icon mr-3"></i>
                            <span class="menu-title">
                                <?php echo e($mapel->nama_mapel); ?> <br>
                                <span class="d-block mt-1">- <?php echo e(optional($mapel->kelas)->nama_kelas); ?></span>
                                <span class="d-block mt-1">- <?php echo e(optional($mapel->tahunAjaran)->nama_tahun); ?></span>
                            </span>
                        </div>
                    </a>
                </li>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



        <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Super Admin')): ?>
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Access</span>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-operator')): ?>
            <li class="nav-items <?php echo e(Route::is('users.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('users.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-user-shield menu-icon mr-3"></i>
                        <span class="menu-title">Data Operator</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-permission')): ?>
            <li class="nav-items <?php echo e(Route::is('permissions.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('permissions.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-key menu-icon mr-3"></i>
                        <span class="menu-title">Data Permission</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-data-role')): ?>
            <li class="nav-items <?php echo e(Route::is('roles.*') ? 'active' : ''); ?>">
                <a class="nav-link" href="<?php echo e(route('roles.index')); ?>">
                    <div class="menu-item">
                        <i class="fas fa-users-cog menu-icon mr-3"></i>
                        <span class="menu-title">Data Role</span>
                    </div>
                </a>
            </li>
        <?php endif; ?>

    </ul>
</nav>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/sidebar.blade.php ENDPATH**/ ?>