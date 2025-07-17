<?php
    $kelas = $wali->kelas;
    $tahunAjaran = $wali->tahunAjaran;
    $slugKelas = Str::slug(optional($kelas)->nama_kelas ?? '');
    $slugTahunAjaran = str_replace('/', '-', optional($tahunAjaran)->nama_tahun ?? '');
    $currentPath = request()->path();

    $activePatterns = [
        "*daftar-siswa/$slugKelas/$slugTahunAjaran",
        "*daftar-mapel/$slugKelas/$slugTahunAjaran",
        "*export-nilai/$slugKelas/$slugTahunAjaran",
    ];

    $isActive = collect($activePatterns)->contains(fn($pattern) => Str::is($pattern, $currentPath));
?>

<?php if($kelas && $tahunAjaran): ?>
    <li class="nav-items <?php echo e($isActive ? 'active' : ''); ?>">
        <a class="nav-link"
            href="<?php echo e(route('daftar-siswa.index', [
                'kelas' => $slugKelas,
                'tahunAjaran' => $slugTahunAjaran,
            ])); ?>">
            <div class="menu-item">
                <i class="fas fa-chalkboard menu-icon mr-3 <?php echo e($isDraft ? 'text-secondary' : ''); ?>"></i>
                <span class="menu-title <?php echo e($isDraft ? 'text-secondary' : ''); ?>">
                    <?php echo e($kelas->nama_kelas); ?><br>
                    <small class="menu-title"
                        style="font-size: 0.75rem; color: <?php echo e($isActive ? '#ffffff' : '#6c757d'); ?>;">
                        T.A: <?php echo e($tahunAjaran->nama_tahun); ?>

                    </small>
                </span>
            </div>
        </a>
    </li>
<?php endif; ?>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/sidebar-walas-item.blade.php ENDPATH**/ ?>