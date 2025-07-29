@extends('layouts.main')

@section('title', 'Daftar Kuis dan Ujian')

@section('content')
    <div class="container py-2">
        <h2 class="mb-4 fw-bold text-center">Daftar Kuis dan Ujian</h2>

        @if ($pertemuanKuisAktif->isEmpty() && $pertemuanKuisDraft->isEmpty())
            <p class="text-center text-muted">Tidak ada kuis</p>
        @endif

        {{-- kuis Aktif --}}
        @if ($pertemuanKuisAktif->isNotEmpty())
            <div class="row">
                @foreach ($pertemuanKuisAktif as $pertemuanKuis)
                    @include('components.frontend.kuis-card', [
                        'pertemuanKuis' => $pertemuanKuis,
                        'isDraft' => false,
                    ])
                @endforeach
            </div>
        @endif

        {{-- kuis Draft --}}
        @if ($pertemuanKuisDraft->isNotEmpty())
            <hr class="my-5">
            <h4 class="text-center text-muted mb-3">Kuis dari Mata Pelajaran Tidak Aktif</h4>
            <div class="row">
                @foreach ($pertemuanKuisDraft as $pertemuanKuis)
                    @include('components.frontend.kuis-card', [
                        'pertemuanKuis' => $pertemuanKuis,
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
