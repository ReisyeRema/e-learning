<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Data Pelajaran</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    @include('includes.frontend.style')
    @include('includes.frontend.style-kelas')

</head>

<body>
    @include('components.frontend.header')

    <div class="overlay" id="overlay"></div>

    <main class="main py-5">
        <div class="container">
            <h2 class="mb-5 fw-bold text-center">Mata Pelajaran - {{ $kelas->nama_kelas }}</h2>
            <div class="row">
                {{-- Sidebar Tahun Ajaran --}}
                <div class="col-md-3 mb-4">
                    <div class="bg-sidebar rounded shadow-sm p-3">
                        <h5 class="mb-3">Filter Tahun Ajaran</h5>
                        <div class="list-group">
                            @foreach ($tahunAjaranList as $tahun)
                                <a href="{{ route('kelas.show', ['id' => $kelas->id, 'tahun_ajaran_id' => $tahun->id]) }}"
                                    class="list-group-item list-group-item-action {{ $tahunAjaranDipilih == $tahun->id ? 'active' : '' }}">
                                    {{ $tahun->nama_tahun }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- Daftar Pembelajaran --}}
                <div class="col-md-9">
                    <div class="row row-cols-1 row-cols-md-2 g-4">
                        @forelse ($pembelajaran as $pelajaran)
                            <div class="col">
                                <div class="card h-100 shadow-sm border-0">
                                    @if ($pelajaran->cover)
                                        <img src="{{ asset('storage/covers/' . $pelajaran->cover) }}"
                                            alt="Cover {{ $pelajaran->nama_mapel }}" class="card-img-top"
                                            style="height: 180px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('assets/img/e-learning.png') }}" alt="Default Cover"
                                            class="card-img-top" style="height: 180px; object-fit: cover;">
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $pelajaran->nama_mapel }}</h5>
                                        <p class="mb-1 text-muted"><i class="fas fa-calendar-alt me-1"></i> Tahun
                                            Ajaran: <strong>{{ $pelajaran->tahunAjaran->nama_tahun ?? '-' }}</strong>
                                        </p>
                                        <p class="mb-2 text-muted"><i class="fas fa-chalkboard-teacher me-1"></i>
                                            {{ $pelajaran->guru->name ?? 'Tidak ada data' }}</p>

                                        <div class="mt-auto">
                                            @php
                                                $isEnrolled = isset($enrollments[$pelajaran->id]);
                                                $isPending =
                                                    $isEnrolled && $enrollments[$pelajaran->id]->status === 'pending';
                                                $isApproved =
                                                    $isEnrolled && $enrollments[$pelajaran->id]->status === 'approved';
                                            @endphp

                                            @if (!$isEnrolled)
                                                <button class="btn btn-primary w-100"
                                                    onclick="enrollSiswa({{ $pelajaran->id }})">Daftar</button>
                                            @elseif ($isPending)
                                                <span
                                                    class="bg-warning text-dark d-inline-block px-3 py-1 mt-2 rounded small">Menunggu
                                                    persetujuan guru</span>
                                            @elseif ($isApproved)
                                                <span
                                                    class="bg-success text-white d-inline-block px-3 py-1 mt-2 rounded small">Anda
                                                    sudah terdaftar</span>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-warning text-center">Tidak ada mata pelajaran untuk tahun ajaran
                                    ini.</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </main>


    @include('components.frontend.footer')
    @include('includes.frontend.script')

    <script>
        function enrollSiswa(pembelajaranId) {
            Swal.fire({
                title: 'Konfirmasi Pendaftaran',
                text: "Apakah Anda yakin ingin mendaftar ke mata pelajaran ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Daftar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('enroll.store') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            },
                            body: JSON.stringify({
                                pembelajaran_id: pembelajaranId
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: data.message,
                                confirmButtonColor: '#198754'
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Gagal mendaftar. Silakan coba lagi.',
                                confirmButtonColor: '#d33'
                            });
                        });
                }
            });
        }
    </script>

</body>

</html>
