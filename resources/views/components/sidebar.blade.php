@php
    $user = Auth::user();
    $mataPelajaran =
        $user && $user->hasRole('Guru') ? \App\Models\Pembelajaran::where('guru_id', $user->id)->get() : collect();

    $currentRoute = Route::currentRouteName();
@endphp


<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-items {{ Route::is('dashboard') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('dashboard') }}">
                <div class="menu-item">
                    <i class="fas fa-tachometer-alt menu-icon mr-3"></i>
                    <span class="menu-title">Dashboard</span>
                </div>
            </a>
        </li>

        @can('menu-profile-sekolah')
            <li class="nav-items {{ Route::is('profilesekolah.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('profilesekolah.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-school menu-icon mr-3"></i>
                        <span class="menu-title">Profile Sekolah</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-kurikulum')
            <li class="nav-items {{ Route::is('kurikulum.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kurikulum.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-book menu-icon mr-3"></i>
                        <span class="menu-title">Kurikulum</span>
                    </div>
                </a>
            </li>
        @endcan

        @hasanyrole('Guru|Admin')
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Data</span>
            </li>
        @endhasanyrole


        @can('menu-data-kelas')
            <li class="nav-items {{ Route::is('kelas.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kelas.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard menu-icon mr-3"></i>
                        <span class="menu-title">Data Kelas</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-tahun-ajar')
            <li class="nav-items {{ Route::is('tahun-ajaran.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tahun-ajaran.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-calendar menu-icon mr-3"></i>
                        <span class="menu-title">Data Tahun Ajar</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-guru')
            <li class="nav-items {{ Route::is('guru.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('guru.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Data Guru</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-siswa')
            <li class="nav-items {{ Route::is('siswa.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('siswa.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-user-graduate menu-icon mr-3"></i>
                        <span class="menu-title">Data Siswa</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-pembelajaran')
            <li class="nav-items {{ Route::is('pembelajaran.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('pembelajaran.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-chalkboard-teacher menu-icon mr-3"></i>
                        <span class="menu-title">Pembelajaran</span>
                    </div>
                </a>
            </li>
        @endcan

        {{-- <li class="nav-items">
            <a class="nav-link" href="{{ route('enroll-siswa.index') }}">
                <div class="menu-item">
                    <i class="fas fa-user-plus menu-icon mr-3"></i>
                    <span class="menu-title">Enroll Siswa</span>
                </div>
            </a>
        </li> --}}

        @can('menu-data-materi')
            <li class="nav-items {{ Route::is('materi.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('materi.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-book-open menu-icon mr-3"></i>
                        <span class="menu-title">Data Materi</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-tugas')
            <li class="nav-items {{ Route::is('tugas.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tugas.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-tasks menu-icon mr-3"></i>
                        <span class="menu-title">Data Tugas</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-kuis')
            <li
                class="nav-items {{ request()->routeIs('kuis.index') || request()->routeIs('soal.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kuis.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-question-circle menu-icon mr-3"></i>
                        <span class="menu-title">Data Kuis</span>
                    </div>
                </a>
            </li>
        @endcan

        {{-- <li class="nav-items ">
            <a class="nav-link" href="">
                <div class="menu-item">
                    <i class="fas fa-clipboard-check menu-icon mr-3"></i>
                    <span class="menu-title">Data Absensi</span>
                </div>
            </a>
        </li> --}}


        @role('Guru')
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Mata Pelajaran</span>
            </li>
        @endrole


        {{-- @foreach ($mataPelajaran as $mapel)
            @php

                // Ubah nama mapel & kelas ke format slug
                $slugMapel = Str::slug($mapel->nama_mapel);
                $slugKelas = Str::slug(optional($mapel->kelas)->nama_kelas ?? '');

                // Pastikan tahun ajaran dikonversi ke format "20232024"
                $tahunAjaranRaw = optional($mapel->tahunAjaran)->nama_tahun ?? '';
                $slugTahunAjaran = $tahunAjaranRaw ? str_replace('/', '-', $tahunAjaranRaw) : '';

                // Tentukan route yang aktif
                $currentRoute = request()->path();
                $activeRoutes = [
                    "submit-materi/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "submit-tugas/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "submit-kuis/$slugMapel/$slugKelas/$slugTahunAjaran",
                    "siswa-kelas/$slugMapel/$slugKelas/$slugTahunAjaran",
                ];
                $isActive = in_array($currentRoute, $activeRoutes);
            @endphp

            @can('menu-submit-materi')
                <li class="nav-items {{ $isActive ? 'active' : '' }}">
                    <a class="nav-link"
                        href="{{ route('submit-materi.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran]) }}">
                        <div class="menu-item">
                            <i class="fas fa-clipboard-check menu-icon mr-3"></i>
                            <span class="menu-title">
                                {{ $mapel->nama_mapel }} <br>
                                <span class="d-block mt-1">- {{ optional($mapel->kelas)->nama_kelas }}</span>
                                <span class="d-block mt-1">- {{ optional($mapel->tahunAjaran)->nama_tahun }}</span>
                            </span>
                        </div>
                    </a>
                </li>
            @endcan
        @endforeach --}}

        @foreach ($mataPelajaran as $mapel)
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
        @endforeach



        @role('Super Admin')
            <li class="mt-3">
                <span class="app-menu__item app-menu__label text-primary">Management Access</span>
            </li>
        @endrole

        @can('menu-data-operator')
            <li class="nav-items {{ Route::is('users.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('users.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-user-shield menu-icon mr-3"></i>
                        <span class="menu-title">Data Operator</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-permission')
            <li class="nav-items {{ Route::is('permissions.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('permissions.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-key menu-icon mr-3"></i>
                        <span class="menu-title">Data Permission</span>
                    </div>
                </a>
            </li>
        @endcan

        @can('menu-data-role')
            <li class="nav-items {{ Route::is('roles.*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('roles.index') }}">
                    <div class="menu-item">
                        <i class="fas fa-users-cog menu-icon mr-3"></i>
                        <span class="menu-title">Data Role</span>
                    </div>
                </a>
            </li>
        @endcan

    </ul>
</nav>
