@php
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
@endphp

@if ($kelas && $tahunAjaran)
    <li class="nav-items {{ $isActive ? 'active' : '' }}">
        <a class="nav-link"
            href="{{ route('daftar-siswa.index', [
                'kelas' => $slugKelas,
                'tahunAjaran' => $slugTahunAjaran,
            ]) }}">
            <div class="menu-item">
                <i class="fas fa-chalkboard menu-icon mr-3 {{ $isDraft ? 'text-secondary' : '' }}"></i>
                <span class="menu-title {{ $isDraft ? 'text-secondary' : '' }}">
                    {{ $kelas->nama_kelas }}<br>
                    <small class="menu-title"
                        style="font-size: 0.75rem; color: {{ $isActive ? '#ffffff' : '#6c757d' }};">
                        T.A: {{ $tahunAjaran->nama_tahun }}
                    </small>
                </span>
            </div>
        </a>
    </li>
@endif
