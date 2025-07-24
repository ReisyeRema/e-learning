<?php
    $slugMapel = Str::slug($mapel->nama_mapel);
    $slugKelas = Str::slug(optional($mapel->kelas)->nama_kelas ?? '');
    $tahunAjaranRaw = optional($mapel->tahunAjaran)->nama_tahun ?? '';
    $slugTahunAjaran = $tahunAjaranRaw ? str_replace('/', '-', $tahunAjaranRaw) : '';
    $slugSemester = Str::slug($mapel->semester);
    $currentPath = request()->path();

    $activePatterns = [
        "*submit-materi/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
        "*submit-tugas/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
        "*submit-kuis/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
        "*siswa-kelas/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
        "*absensi/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
        "*absensi/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester/detail-absensi",
        "*submit-tugas/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester/list-tugas",
        "*submit-kuis/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester/list-kuis",
        "*pertemuan-kuis/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester/hasil-kuis/*/*",
        "*rekap-akhir/$slugMapel/$slugKelas/$slugTahunAjaran/$slugSemester",
    ];

    $isActive = collect($activePatterns)->contains(fn($pattern) => Str::is($pattern, $currentPath));
?>

<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('menu-submit-materi')): ?>
    <li class="nav-items <?php echo e($isActive ? 'active' : ''); ?>">
        <a class="nav-link"
            href="<?php echo e(route('submit-materi.show', [
                'mapel' => $slugMapel,
                'kelas' => $slugKelas,
                'tahunAjaran' => $slugTahunAjaran,
                'semester' => $slugSemester
            ])); ?>">
            <div class="menu-item">
                <i class="fas fa-clipboard-check menu-icon mr-3 <?php echo e($isDraft ? 'text-secondary' : ''); ?>"></i>
                <span class="menu-title <?php echo e($isDraft ? 'text-secondary' : ''); ?>">
                    <?php echo e($mapel->nama_mapel); ?><br>
                    <span class="d-block mt-1">- <?php echo e(optional($mapel->kelas)->nama_kelas); ?></span>
                    <span class="d-block mt-1">- <?php echo e(optional($mapel->tahunAjaran)->nama_tahun); ?></span>
                    <span class="d-block mt-1">- <?php echo e($mapel->semester); ?></span>
                    <?php if($isDraft): ?>
                        <span class="badge badge-secondary d-block mt-2">DRAFT</span>
                    <?php endif; ?>
                </span>
            </div>
        </a>
    </li>
<?php endif; ?>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/sidebar-mapel-item.blade.php ENDPATH**/ ?>