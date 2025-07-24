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

@php
    use Illuminate\Support\Str;
    use App\Models\Pembelajaran;
    use App\Models\TahunAjaran;

    // Tangkap parameter dari URL
    $mapelAktif = request()->route('mapel');
    $kelasAktif = request()->route('kelas');
    $tahunAjaranAktif = request()->route('tahunAjaran')
        ? str_replace('-', '/', request()->route('tahunAjaran'))
        : TahunAjaran::latest()->first()->nama_tahun;

    // Cari data pembelajaran yang sesuai
    $currentPembelajaran = Pembelajaran::whereRaw("LOWER(REPLACE(nama_mapel, ' ', '-')) = ?", [$mapelAktif])
        ->whereHas('kelas', function ($query) use ($kelasAktif) {
            $query->whereRaw("LOWER(REPLACE(nama_kelas, ' ', '-')) = ?", [$kelasAktif]);
        })
        ->whereHas('tahunAjaran', function ($query) use ($tahunAjaranAktif) {
            $query->where('nama_tahun', $tahunAjaranAktif);
        })
        ->first();

    // Generate slug yang valid
    $mapelSlug = $currentPembelajaran ? Str::slug($currentPembelajaran->nama_mapel) : 'default-mapel';
    $kelasSlug =
        $currentPembelajaran && $currentPembelajaran->kelas
            ? Str::slug($currentPembelajaran->kelas->nama_kelas)
            : 'default-kelas';
    $tahunAjaranSlug = $tahunAjaranAktif ? str_replace('/', '-', $tahunAjaranAktif) : 'default-tahun';
    $semesterSlug = $currentPembelajaran && $currentPembelajaran->semester
        ? Str::slug($currentPembelajaran->semester)
        : 'default-semester';
@endphp

<div class="container">
    <div class="d-flex justify-content-center gap-3 my-4">
        <a href="{{ route('submit-materi.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*submit-materi/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">
            Materi
        </a>
        <a href="{{ route('submit-tugas.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*submit-tugas/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">Tugas</a>
        <a href="{{ route('submit-kuis.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*submit-kuis/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">Kuis</a>
        <a href="{{ route('absensi.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*absensi/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">Absensi</a>
        <a href="{{ route('siswa-kelas.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*siswa-kelas/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">Daftar
            Siswa</a>
        <a href="{{ route('rekap-akhir.show', ['mapel' => $mapelSlug, 'kelas' => $kelasSlug, 'tahunAjaran' => $tahunAjaranSlug, 'semester' => $semesterSlug]) }}"
            class="btn btn-sm btn-custom {{ request()->is('*rekap-akhir/*') ? 'btn-primary' : 'btn-outline-primary' }} mr-3">Rekapitulasi Nilai Akhir</a>
    </div>
</div>
