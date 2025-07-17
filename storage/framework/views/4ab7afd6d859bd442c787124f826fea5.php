<style>
    .btn-custom {
        transition: background 0.3s ease-in-out, color 0.3s ease-in-out;
    }

    .btn-success:hover {
        background: #218838 !important;
        color: white !important;
    }

    .btn-dark:hover {
        background: #343a40 !important;
        color: white !important;
    }

    .btn-secondary:hover {
        background: #6c757d !important;
        color: white !important;
    }
</style>

<?php
    use Illuminate\Support\Str;
    use App\Models\WaliKelas;
    use App\Models\TahunAjaran;

    // Tangkap parameter dari URL
    $kelasAktif = request()->route('kelas');
    $tahunAjaranAktif = request()->route('tahunAjaran')
        ? str_replace('-', '/', request()->route('tahunAjaran'))
        : TahunAjaran::latest()->first()->nama_tahun;

    // Cari data pembelajaran yang sesuai
    $currentWalas = WaliKelas::whereHas('kelas', function ($query) use ($kelasAktif) {
            $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelasAktif]);
        })
        ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranAktif) {
            $query->where('nama_tahun', $tahunAjaranAktif);
        })
        ->first();

    // Generate slug yang valid
    $kelasSlug =
        $currentWalas && $currentWalas->kelas
            ? Str::slug($currentWalas->kelas->nama_kelas)
            : 'default-kelas';
    $tahunAjaranSlug = $tahunAjaranAktif ? str_replace('/', '-', $tahunAjaranAktif) : 'default-tahun';
   
?>

<div class="container">
    <div class="d-flex justify-content-center gap-3 my-4">
        <a href="<?php echo e(route('daftar-siswa.index', ['kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug])); ?>"
            class="btn btn-sm btn-custom <?php echo e(request()->is('*daftar-siswa/*') ? 'btn-primary' : 'btn-outline-primary'); ?> mr-3">
            Daftar Siswa
        </a>
        <a href="<?php echo e(route('daftar-mapel.index', ['kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug])); ?>"
            class="btn btn-sm btn-custom <?php echo e(request()->is('*daftar-mapel/*') ? 'btn-primary' : 'btn-outline-primary'); ?> mr-3">
            Daftar Mata Pelajaran
        </a>
        <a href="<?php echo e(route('export-nilai.index', ['kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug])); ?>"
            class="btn btn-sm btn-custom <?php echo e(request()->is('*export-nilai/*') ? 'btn-primary' : 'btn-outline-primary'); ?> mr-3">
            Export Nilai
        </a>
        
    </div>
</div>
<?php /**PATH D:\DATA MATKUL\SEMESTER 6\TA\PROJECT\e-learn-laravel\resources\views/components/nav-walas.blade.php ENDPATH**/ ?>