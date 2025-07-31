@extends('layouts.main')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Mata Pelajaran</h2>

        <div class="d-flex justify-content-center mb-2">
            <form method="GET" action="{{ route('mata-pelajaran.index') }}" class="mb-4">
                <div class="row g-2">

                    <div class="col-md-4">
                        <select name="tahun_ajaran" class="form-select">
                            <option value="">-- Semua Tahun Ajaran --</option>
                            @foreach ($tahunAjaranList as $tahun)
                                <option value="{{ $tahun->id }}"
                                    {{ request('tahun_ajaran') == $tahun->id ? 'selected' : '' }}>
                                    {{ $tahun->nama_tahun }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <input type="text" name="mapel" value="{{ request('mapel') }}" class="form-control"
                            placeholder="Nama Mapel">
                    </div>

                    {{-- <div class="col-md-3">
                        <select name="kelas" class="form-select">
                            <option value="">-- Semua Kelas --</option>
                            @foreach ($kelasList as $kelas)
                                <option value="{{ $kelas->id }}" {{ request('kelas') == $kelas->id ? 'selected' : '' }}>
                                    {{ $kelas->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div> --}}

                    <div class="col-md-3 mt-2">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('mata-pelajaran.index') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </div>
            </form>
        </div>

        @if ($enrollments->isEmpty())
            <p class="text-center text-muted">Tidak ada mata pelajaran</p>
        @else
            <div class="row">
                @foreach ($enrollments as $enrollment)
                    <div class="col-12 mb-4">

                        @php
                            $slugMapel = Str::slug($enrollment->pembelajaran->nama_mapel);
                            $slugKelas = Str::slug($enrollment->pembelajaran->kelas->nama_kelas);
                            $slugTahunAjaran = str_replace(
                                '/',
                                '-',
                                $enrollment->pembelajaran->tahunAjaran->nama_tahun,
                            );
                            $slugSemester = Str::slug($enrollment->pembelajaran->semester);
                        @endphp

                        <a href="{{ route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran, 'semester' => $slugSemester]) }}"
                            class="text-decoration-none text-dark">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect">
                                <div class="row g-3 align-items-center">
                                    <!-- Gambar (Sebelah Kiri) -->
                                    <div class="col-md-3">
                                        <img src="{{ $enrollment->pembelajaran->cover &&
                                        file_exists(public_path('storage/covers/' . $enrollment->pembelajaran->cover))
                                            ? asset('storage/covers/' . $enrollment->pembelajaran->cover)
                                            : asset('assets/img/default-cover.png') }}"
                                            class="img-fluid rounded" alt="Cover"
                                            style="height: 150px; object-fit: cover; width: 100%;">
                                    </div>

                                    <!-- Konten (Sebelah Kanan) -->
                                    <div class="col-md-9">
                                        <h5 class="fw-bold text-success">{{ $enrollment->pembelajaran->nama_mapel }} -
                                            {{ $enrollment->pembelajaran->kelas->nama_kelas }} -
                                            {{ $enrollment->pembelajaran->semester }} -
                                            {{ $enrollment->pembelajaran->tahunAjaran->nama_tahun }}
                                        </h5>
                                        <p>
                                            <span
                                                class="badge bg-secondary">{{ $enrollment->pembelajaran->guru->name }}</span>
                                        </p>


                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small">
                                                <span class="fw-bold">{{ $enrollment->progress }}% Completed</span>
                                                <span class="text-muted">
                                                    {{ $enrollment->progress < 100 ? 'Belum Selesai' : 'Selesai' }}
                                                </span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $enrollment->progress }}%;"
                                                    aria-valuenow="{{ $enrollment->progress }}" aria-valuemin="0"
                                                    aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div>

                                        <hr class="profile-divider">

                                        <!-- Info -->
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small text-muted">
                                                📚 {{ $enrollment->pembelajaran->pertemuan_materi_count }} Materi • 👥
                                                {{ $enrollment->pembelajaran->enrollments_count }} Siswa
                                            </span>
                                            <span class="text-success small">Klik untuk melihat detail</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach

                @if ($draftEnrollments->isNotEmpty())
                    <hr class="my-5">
                    <h4 class="text-center text-muted">📄 Draft / Belum Aktif</h4>
                    <div class="row">
                        @foreach ($draftEnrollments as $enrollment)
                            @php
                                $slugMapel = Str::slug($enrollment->pembelajaran->nama_mapel);
                                $slugKelas = Str::slug($enrollment->pembelajaran->kelas->nama_kelas);
                                $slugTahunAjaran = str_replace(
                                    '/',
                                    '-',
                                    $enrollment->pembelajaran->tahunAjaran->nama_tahun,
                                );
                                $slugSemester = Str::slug($enrollment->pembelajaran->semester);
                            @endphp

                            <div class="col-12 mb-4">
                                <a href="{{ route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran, 'semester' => $slugSemester]) }}"
                                    class="text-decoration-none text-muted">
                                    <div class="card shadow-sm border-20 rounded p-3 bg-light" style="opacity: 0.6;">
                                        <div class="row g-3 align-items-center">
                                            <div class="col-md-3">
                                                {{-- <img src="{{ asset('storage/covers/' . $enrollment->pembelajaran->cover) }}"
                                                    class="img-fluid rounded"
                                                    style="height: 150px; object-fit: cover; width: 100%;" alt="Cover"> --}}
                                                <img src="{{ $enrollment->pembelajaran->cover &&
                                                file_exists(public_path('storage/covers/' . $enrollment->pembelajaran->cover))
                                                    ? asset('storage/covers/' . $enrollment->pembelajaran->cover)
                                                    : asset('images/default-cover.png') }}"
                                                    class="img-fluid rounded"
                                                    style="height: 150px; object-fit: cover; width: 100%;" alt="Cover">

                                            </div>
                                            <div class="col-md-9">
                                                <h5 class="fw-bold">{{ $enrollment->pembelajaran->nama_mapel }} -
                                                    {{ $enrollment->pembelajaran->kelas->nama_kelas }} -
                                                    {{ $enrollment->pembelajaran->semester }}</h5>
                                                <p class="mb-1">
                                                    <span
                                                        class="badge bg-secondary">{{ $enrollment->pembelajaran->guru->name }}</span>
                                                </p>
                                                <small class="fst-italic text-danger">Mata pelajaran ini tidak aktif
                                                    (draft)
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        @endif
    </div>
@endsection
