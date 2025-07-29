@extends('layouts.main')

@section('title', 'Daftar Tugas')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Tugas</h2>

        @if ($pertemuanTugasAktif->isEmpty() && $pertemuanTugasDraft->isEmpty())
            <p class="text-center text-muted">Tidak ada tugas</p>
        @endif

        {{-- Tugas Aktif --}}
        @if ($pertemuanTugasAktif->isNotEmpty())
            <div class="row">
                @foreach ($pertemuanTugasAktif as $pertemuanTugas)
                    @include('components.frontend.tugas-card', [
                        'pertemuanTugas' => $pertemuanTugas,
                        'isDraft' => false,
                    ])
                @endforeach
            </div>
        @endif

        {{-- Tugas Draft --}}
        @if ($pertemuanTugasDraft->isNotEmpty())
            <hr class="my-5">
            <h4 class="text-center text-muted mb-3">Tugas dari Mata Pelajaran Tidak Aktif</h4>
            <div class="row">
                @foreach ($pertemuanTugasDraft as $pertemuanTugas)
                    @include('components.frontend.tugas-card', [
                        'pertemuanTugas' => $pertemuanTugas,
                        'isDraft' => true,
                    ])
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
