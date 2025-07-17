@php
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
@endphp


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">

        @if (session('active_role') === 'Wali Kelas')
            @php
                $user = Auth::user();
            @endphp

            {{-- Card Profile --}}
            <li class="nav-item px-3">
                <div class="card border-0 shadow-sm mb-3 rounded-xl text-center"
                    style="background: linear-gradient(135deg, #007bff, #6610f2);">
                    <div class="card-body d-flex flex-column justify-content-center align-items-center p-2">
                        <div class="font-weight-bold text-white mb-1" style="font-size: 1rem;">
                            {{ $user->name }}
                        </div>
                        <div class="text-white small" style="font-size: 0.85rem;">
                            {{ ucfirst(str_replace('_', ' ', session('active_role'))) }}
                        </div>
                    </div>
                </div>
            </li>
        @endif

        <li class="nav-items {{ Route::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="menu-item">
                    <i class="fas fa-tachometer-alt menu-icon mr-3"></i>
                    <span class="menu-title">Dashboard</span>
                </div>
            </a>
        </li>

        @if (hasPermissionForActiveRole('menu-data-materi') ||
                hasPermissionForActiveRole('menu-profile-sekolah') ||
                hasPermissionForActiveRole('menu-data-kurikulum') ||
                hasPermissionForActiveRole('menu-data-kelas') ||
                hasPermissionForActiveRole('menu-data-tahun-ajar') ||
                hasPermissionForActiveRole('menu-data-guru') ||
                hasPermissionForActiveRole('menu-data-walas') ||
                hasPermissionForActiveRole('menu-data-siswa') ||
                hasPermissionForActiveRole('menu-data-tugas') ||
                hasPermissionForActiveRole('menu-data-kuis') ||
                hasPermissionForActiveRole('menu-pembelajaran'))
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Data</span>
            </li>
        @endif


        @if (hasPermissionForActiveRole('menu-profile-sekolah'))
            <li class="nav-items {{ Route::is('profilesekolah.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profilesekolah.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-school menu-icon mr-3"></i>
                        <span class="menu-title">Profile Sekolah</span>
                    </div>
                </a>
            </li>
        @endif


        @if (hasPermissionForActiveRole('menu-data-kurikulum'))
            <li class="nav-items {{ Route::is('kurikulum.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kurikulum.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-book menu-icon mr-3"></i>
                        <span class="menu-title">Kurikulum</span>
                    </div>
                </a>
            </li>
        @endif


        @if (hasPermissionForActiveRole('menu-data-kelas'))
            <li class="nav-items {{ Route::is('kelas.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kelas.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard menu-icon mr-3"></i>
                        <span class="menu-title">Data Kelas</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-tahun-ajar'))
            <li class="nav-items {{ Route::is('tahun-ajaran.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tahun-ajaran.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-calendar menu-icon mr-3"></i>
                        <span class="menu-title">Data Tahun Ajar</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-guru'))
            <li class="nav-items {{ Route::is('guru.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('guru.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Data Guru</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-walas'))
            <li class="nav-items {{ Route::is('wali-kelas.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('wali-kelas.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-users menu-icon mr-3"></i>
                        <span class="menu-title">Data Wali Kelas</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-siswa'))
            <li class="nav-items {{ Route::is('siswa.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('siswa.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-user-graduate menu-icon mr-3"></i>
                        <span class="menu-title">Data Siswa</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-pembelajaran'))
            <li class="nav-items {{ Route::is('pembelajaran.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pembelajaran.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Pembelajaran</span>
                    </div>
                </a>
            </li>
        @endif


        @if (hasPermissionForActiveRole('menu-data-materi'))
            <li class="nav-items {{ Route::is('materi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('materi.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-book-open menu-icon mr-3"></i>
                        <span class="menu-title">Data Materi</span>
                    </div>
                </a>
            </li>
        @endif


        @if (hasPermissionForActiveRole('menu-data-tugas'))
            <li class="nav-items {{ Route::is('tugas.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tugas.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-tasks menu-icon mr-3"></i>
                        <span class="menu-title">Data Tugas</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-kuis'))
            <li
                class="nav-items {{ request()->routeIs('kuis.index') || request()->routeIs('soal.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kuis.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-question-circle menu-icon mr-3"></i>
                        <span class="menu-title">Data Kuis</span>
                    </div>
                </a>
            </li>
        @endif


        @if (session('active_role') === 'Guru')
            {{-- Menampilkan Label jika ada data --}}
            @if ($mataPelajaranAktif->isNotEmpty())
                <li class="mt-3">
                    <span class="app-menu__item app-menu__label text-primary">Mata Pelajaran Aktif</span>
                </li>
            @endif

            @foreach ($mataPelajaranAktif as $mapel)
                @include('components.sidebar-mapel-item', ['mapel' => $mapel, 'isDraft' => false])
            @endforeach

            @if ($mataPelajaranDraft->isNotEmpty())
                <li class="mt-3" x-data="{ openDraft: false }">
                    <div @click="openDraft = !openDraft"
                        class="app-menu__item app-menu__label text-secondary d-flex justify-content-between align-items-center cursor-pointer">
                        <span>Mata Pelajaran (Draft)</span>
                        <i :class="openDraft ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </div>

                    <ul x-show="openDraft" x-transition>
                        @foreach ($mataPelajaranDraft as $mapel)
                            @include('components.sidebar-mapel-item', [
                                'mapel' => $mapel,
                                'isDraft' => true,
                            ])
                        @endforeach
                    </ul>
                </li>
            @endif
        @endif


        {{-- @foreach ($mataPelajaran as $mapel)
            @php
                $slugMapel = Str::slug($mapel->nama_mapel);
                $slugKelas = Str::slug(optional($mapel->kelas)->nama_kelas ?? '');
                $tahunAjaranRaw = optional($mapel->tahunAjaran)->nama_tahun ?? '';
                $slugTahunAjaran = $tahunAjaranRaw ? str_replace('/', '-', $tahunAjaranRaw) : '';
                $slugSemester = Str::slug($mapel->semester);

                // Ambil full path saat ini (tanpa domain)
                $currentPath = request()->path(); // contoh: 'guru/submit-materi/ipa-7/a/2023-2024'

                // Buat pattern pencarian yang fleksibel (support prefix)
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
                ];

                $isActive = false;
                foreach ($activePatterns as $pattern) {
                    if (Str::is($pattern, $currentPath)) {
                        $isActive = true;
                        break;
                    }
                }
            @endphp

            @can('menu-submit-materi')
                <li class="nav-items {{ $isActive ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('submit-materi.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran, 'semester' => $slugSemester]) }}">
                        <div class="menu-item">
                            <i class="fas fa-clipboard-check menu-icon mr-3"></i>
                            <span class="menu-title">
                                {{ $mapel->nama_mapel }} <br>
                                <span class="d-block mt-1">- {{ optional($mapel->kelas)->nama_kelas }}</span>
                                <span class="d-block mt-1">- {{ optional($mapel->tahunAjaran)->nama_tahun }}</span>
                                <span class="d-block mt-1">- {{ $mapel->semester }}</span>
                            </span>
                        </div>
                    </a>
                </li>
            @endcan
        @endforeach --}}


        @if (hasPermissionForActiveRole('menu-data-operator') ||
                hasPermissionForActiveRole('menu-data-permission') ||
                hasPermissionForActiveRole('menu-data-role'))
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Access</span>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-operator'))
            <li class="nav-items {{ Route::is('users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-user-shield menu-icon mr-3"></i>
                        <span class="menu-title">Data Operator</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-permission'))
            <li class="nav-items {{ Route::is('permissions.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('permissions.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-key menu-icon mr-3"></i>
                        <span class="menu-title">Data Permission</span>
                    </div>
                </a>
            </li>
        @endif

        @if (hasPermissionForActiveRole('menu-data-role'))
            <li class="nav-items {{ Route::is('roles.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-users-cog menu-icon mr-3"></i>
                        <span class="menu-title">Data Role</span>
                    </div>
                </a>
            </li>
        @endif


        @if (session('active_role') === 'Wali Kelas')

            @if ($waliKelasAktif->isNotEmpty())
                <li class="mt-2">
                    <span class="app-menu__item app-menu__label text-primary">Kelas Aktif</span>
                </li>

                @foreach ($waliKelasAktif as $wali)
                    @include('components.sidebar-walas-item', ['wali' => $wali, 'isDraft' => false])
                @endforeach
            @endif

            @if ($waliKelasDraft->isNotEmpty())
                <li class="mt-3" x-data="{ openDraftWalas: false }">
                    <div @click="openDraftWalas = !openDraftWalas"
                        class="app-menu__item app-menu__label text-secondary d-flex justify-content-between align-items-center cursor-pointer">
                        <span>Kelas (Draft)</span>
                        <i :class="openDraftWalas ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
                    </div>

                    <ul x-show="openDraftWalas" x-transition>
                        @foreach ($waliKelasDraft as $wali)
                            @include('components.sidebar-walas-item', ['wali' => $wali, 'isDraft' => true])
                        @endforeach
                    </ul>
                </li>
            @endif

        @endif


    </ul>
</nav>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
