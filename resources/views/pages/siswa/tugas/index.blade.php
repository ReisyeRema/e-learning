@extends('layouts.main')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Tugas</h2>

        @if ($tugasList->isEmpty())
            <p class="text-center text-muted">Tidak ada tugas</p>
        @else
            <div class="row">
                @foreach ($tugasList as $tugas)
                    @foreach ($tugas->pertemuanTugas as $pertemuanTugas)
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect position-relative">

                                @php
                                    $slugMapel = Str::slug($pertemuanTugas->pembelajaran->nama_mapel);
                                    $slugKelas = Str::slug($pertemuanTugas->pembelajaran->kelas->nama_kelas);
                                    $slugTahunAjaran = str_replace(
                                        '/',
                                        '-',
                                        $pertemuanTugas->pembelajaran->tahunAjaran->nama_tahun,
                                    );
                                @endphp

                                <!-- Status di Pojok Kanan Atas -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    @if ($tugas->submitTugas->isNotEmpty())
                                        @php
                                            $submittedAt = strtotime($tugas->submitTugas->first()->created_at);
                                            $deadline = strtotime($pertemuanTugas->deadline);
                                        @endphp
                                        @if ($submittedAt <= $deadline)
                                            <span class="badge bg-success">Terkumpul</span>
                                        @else
                                            <span class="badge bg-warning">Terlambat</span>
                                        @endif
                                    @else
                                        <span class="badge bg-danger">Belum</span>
                                    @endif
                                </div>

                                <div class="row g-1 align-items-center">
                                    <!-- Gambar Tugas (Sebelah Kiri) -->
                                    <div class="col-md-2 text-center">
                                        <img src="{{ asset('assets/img/tugas.png') }}" class="img-fluid rounded"
                                            alt="Gambar tugas" style="width: 80px; height: auto; object-fit: cover;">
                                    </div>


                                    <!-- Konten Tugas (Sebelah Kanan) -->
                                    <div class="col-md-10">
                                        <h5 class="fw-bold text-success mb-2">{{ $tugas->judul }}</h5>
                                        <span class="badge bg-secondary mb-2">
                                            {{ $pertemuanTugas->pembelajaran->nama_mapel }} -
                                            {{ $pertemuanTugas->pembelajaran->kelas->nama_kelas }}
                                        </span>

                                        <p class="mb-2"><strong>Tenggat:</strong>
                                            {{ date('d F Y - H:i', strtotime($pertemuanTugas->deadline)) }}
                                        </p>

                                        @if ($tugas->submitTugas->isNotEmpty())
                                            <p class="mb-1"><strong>Dikumpulkan:</strong>
                                                {{ date('d F Y - H:i', strtotime($tugas->submitTugas->first()->created_at)) }}
                                            </p>
                                        @else
                                            <p class="mb-1"><strong>Dikumpulkan:</strong> -</p>
                                        @endif

                                        <div class="text-end mt-2">
                                            @if ($tugas->submitTugas->isNotEmpty())
                                                <a href="#" class="text-decoration-none fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailTugas{{ $tugas->id }}">Lihat</a>
                                            @else
                                                <a href="{{ route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran]) }}"
                                                    class="text-decoration-none fw-bold">Kumpulkan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- @if ($tugas->submitTugas->isNotEmpty())
                            <div class="modal fade" id="modalDetailTugas{{ $tugas->id }}" tabindex="-1"
                                aria-labelledby="modalDetailTugasLabel{{ $tugas->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetailTugasLabel{{ $tugas->id }}">Detail
                                                Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Judul:</strong> {{ $tugas->judul }}</p>
                                            <p><strong>Dikumpulkan pada:</strong>
                                                {{ date('d F Y - H:i', strtotime($tugas->submitTugas->first()->created_at)) }}
                                            </p>
                                            <p><strong>File Tugas:</strong>
                                                @if ($tugas->submitTugas->first()->url)
                                                    <a href="{{ $tugas->submitTugas->first()->url }}"
                                                        target="_blank">Klik di sini untuk melihat tugas yang dikumpulkan</a>
                                                @elseif ($tugas->submitTugas->first()->file_path)
                                                    <a href="https://drive.google.com/file/d/{{ $tugas->submitTugas->first()->file_path }}/view"
                                                        target="_blank" class="btn btn-primary">Lihat Tugas</a>
                                                @else
                                                    <span class="text-muted">Tidak ada file tersedia</span>
                                                @endif
                                            </p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif --}}

                        @if ($tugas->submitTugas->isNotEmpty())
                            <div class="modal fade" id="modalDetailTugas{{ $tugas->id }}" tabindex="-1"
                                aria-labelledby="modalDetailTugasLabel{{ $tugas->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetailTugasLabel{{ $tugas->id }}">Detail
                                                Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Judul</strong></div>
                                                <div>: {{ $tugas->judul }}</div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Dikumpulkan pada</strong></div>
                                                <div>:
                                                    {{ date('d F Y - H:i', strtotime($tugas->submitTugas->first()->created_at)) }}
                                                </div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>File Tugas</strong></div>
                                                <div>:
                                                    @if ($tugas->submitTugas->first()->url)
                                                        <a href="{{ $tugas->submitTugas->first()->url }}" target="_blank"
                                                            class="btn btn-outline-primary btn-sm">
                                                            Lihat File
                                                        </a>
                                                    @elseif ($tugas->submitTugas->first()->file_path)
                                                        <a href="https://drive.google.com/file/d/{{ $tugas->submitTugas->first()->file_path }}/view"
                                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                                            Lihat File
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Tidak ada file tersedia</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endforeach
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if (session('success'))
                Swal.fire({
                    title: "Berhasil!",
                    text: "{{ session('success') }}",
                    icon: "success",
                    confirmButtonText: "OK"
                });
            @endif
        });
    </script>

@endsection
