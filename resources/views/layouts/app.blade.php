<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title') | Learning Manajement Syste</title>

    @include('includes.backend.css.style')
</head>

<body>
    <div class="container-scroller">

        @include('layouts.navigation')

        <div class="container-fluid page-body-wrapper">

            @include('components.sidebar')

            <div class="main-panel">
                <div class="content-wrapper">
                    @yield('content')
                </div>
            </div>
        </div>

        @include('components.footer')

    </div>

    @include('includes.backend.js.script')

    @stack('scripts')


    <!-- Floating Chat Button -->
    @php
        $showChatRoutes = [
            'submit-materi.show',
            'submit-kuis.show',
            'submit-tugas.show',
            'siswa-kelas.show',
            'absensi.show',
            'detail-absensi.index',
            'list-pertemuan-kuis.index',
            'hasil-kuis.show',
            'list-pertemuan-tugas.index',
        ];
    @endphp

    @if (in_array(Route::currentRouteName(), $showChatRoutes))
        <!-- Floating Chat Button -->
        <a href="{{ route('forum-diskusi-guru.index', [
            'mapel' => Str::slug($pembelajaran->nama_mapel),
            'kelas' => Str::slug($pembelajaran->kelas->nama_kelas),
            'tahunAjaran' => str_replace('/', '-', $pembelajaran->tahunAjaran->nama_tahun),
            'semester' => Str::slug($pembelajaran->semester),
        ]) }}"
            class="chat-button" title="Forum Diskusi">
            <i class="fas fa-comments"></i>
        </a>
    @endif

</body>

</html>
