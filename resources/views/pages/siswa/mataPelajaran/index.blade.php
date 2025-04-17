@extends('layouts.main')

@section('title', 'Mata Pelajaran')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Mata Pelajaran</h2>

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
                        @endphp

                        <a href="{{ route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran]) }}"
                            class="text-decoration-none text-dark">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect">
                                <div class="row g-3 align-items-center">
                                    <!-- Gambar (Sebelah Kiri) -->
                                    <div class="col-md-3">
                                        <img src="{{ asset('storage/covers/' . $enrollment->pembelajaran->cover) }}"
                                            class="img-fluid rounded" alt="Cover"
                                            style="height: 150px; object-fit: cover; width: 100%;">
                                    </div>

                                    <!-- Konten (Sebelah Kanan) -->
                                    <div class="col-md-9">
                                        <h5 class="fw-bold text-success">{{ $enrollment->pembelajaran->nama_mapel }} -
                                            {{ $enrollment->pembelajaran->kelas->nama_kelas }}
                                        </h5>
                                        <p>
                                            <span
                                                class="badge bg-secondary">{{ $enrollment->pembelajaran->guru->name }}</span>
                                        </p>

                                        <!-- Progress Bar -->
                                        {{-- @php
                                            $progress = rand(50, 100);
                                        @endphp
                                        <div class="mb-2">
                                            <div class="d-flex justify-content-between small">
                                                <span class="fw-bold">{{ $progress }}% Completed</span>
                                                <span
                                                    class="text-muted">{{ $progress < 100 ? 'Belum Selesai' : 'Selesai' }}</span>
                                            </div>
                                            <div class="progress" style="height: 6px;">
                                                <div class="progress-bar bg-success" role="progressbar"
                                                    style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                        </div> --}}

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
                                                    aria-valuenow="{{ $enrollment->progress }}"
                                                    aria-valuemin="0" aria-valuemax="100">
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
            </div>
        @endif
    </div>
@endsection
