@extends('layouts.main')

@section('title', 'Daftar Kuis dan Ujian')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Kuis dan Ujian</h2>

        @if ($kuisList->isEmpty())
            <p class="text-center text-muted">Tidak ada tugas</p>
        @else
            <div class="row">
                @foreach ($kuisList as $kuis)
                    @foreach ($kuis->pertemuanKuis as $pertemuanKuis)
                        <div class="col-12 mb-4">
                            <div class="card shadow-sm border-20 rounded p-3 hover-effect position-relative">

                                @php
                                    $slugMapel = Str::slug($pertemuanKuis->pembelajaran->nama_mapel);
                                    $slugKelas = Str::slug($pertemuanKuis->pembelajaran->kelas->nama_kelas);
                                    $slugTahunAjaran = str_replace(
                                        '/',
                                        '-',
                                        $pertemuanKuis->pembelajaran->tahunAjaran->nama_tahun,
                                    );
                                @endphp

                                <!-- Status di Pojok Kanan Atas -->
                                <div class="position-absolute top-0 end-0 mt-2 me-2">
                                    @if ($kuis->hasilKuis->isNotEmpty())
                                        @php
                                            $submittedAt = strtotime($kuis->hasilKuis->first()->created_at);
                                            $deadline = strtotime($pertemuanKuis->deadline);
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
                                        <h5 class="fw-bold text-success mb-2">{{ $kuis->judul }}</h5>
                                        <span class="badge bg-secondary mb-2">
                                            {{ $pertemuanKuis->pembelajaran->nama_mapel }} -
                                            {{ $pertemuanKuis->pembelajaran->kelas->nama_kelas }}
                                        </span>

                                        <p class="mb-2"><strong>Tenggat:</strong>
                                            {{ date('d F Y - H:i', strtotime($pertemuanKuis->deadline)) }}
                                        </p>

                                        @if ($kuis->hasilKuis->isNotEmpty())
                                            <p class="mb-1"><strong>Selesai:</strong>
                                                {{ date('d F Y - H:i', strtotime($kuis->hasilKuis->first()->created_at)) }}
                                            </p>
                                        @else
                                            <p class="mb-1"><strong>Selesai:</strong> -</p>
                                        @endif

                                        <div class="text-end mt-2">
                                            @if ($kuis->hasilKuis->isNotEmpty())
                                                <a href="#" class="text-decoration-none fw-bold"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalDetailTugas{{ $kuis->id }}">Lihat</a>
                                            @else
                                                <a href="{{ route('mata-pelajaran.show', ['mapel' => $slugMapel, 'kelas' => $slugKelas, 'tahunAjaran' => $slugTahunAjaran]) }}"
                                                    class="text-decoration-none fw-bold">Kerjakan</a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if ($kuis->hasilKuis->isNotEmpty())
                            <div class="modal fade" id="modalDetailTugas{{ $kuis->id }}" tabindex="-1"
                                aria-labelledby="modalDetailTugasLabel{{ $kuis->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="modalDetailTugasLabel{{ $kuis->id }}">Detail
                                                Tugas</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Judul</strong></div>
                                                <div>: {{ $kuis->judul }}</div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Selesai pada</strong></div>
                                                <div>: {{ date('d F Y - H:i', strtotime($kuis->hasilKuis->first()->created_at)) }}</div>
                                            </div>
                                            <div class="d-flex mb-2">
                                                <div style="min-width: 150px;"><strong>Nilai</strong></div>
                                                <div>: 
                                                    <span class="badge bg-success fs-8">
                                                        {{ $kuis->hasilKuis->first()->skor_total }}
                                                    </span>
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
