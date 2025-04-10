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

    <main class="main p-5">
        <div class="container">
            <h2>Mata Pelajaran - {{ $kelas->nama_kelas }}</h2>
            <div class="row g-4">
                @foreach ($pembelajaran as $pelajaran)
                    <div class="col-lg-4 col-md-6">
                        <div class="card">
                            @if ($pelajaran->cover)
                                <img src="{{ asset('storage/covers/' . $pelajaran->cover) }}"
                                    alt="Cover {{ $pelajaran->nama_mapel }}" class="card-cover">
                            @else
                                <img src="{{ asset('assets/img/profil.png') }}" alt="Default Cover" class="card-cover">
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ $pelajaran->nama_mapel }}</h5>
                                <p class="card-text">
                                    <i class="fas fa-calendar-alt"></i> Tahun Ajaran:
                                    <strong>{{ $pelajaran->tahunAjaran->nama_tahun ?? 'Tidak ada data' }}</strong>
                                </p>
                                <p>
                                    <span class="badge-guru">
                                        <i class="fas fa-chalkboard-teacher"></i>
                                        {{ $pelajaran->guru->name ?? 'Tidak ada data' }}
                                    </span>
                                </p>

                                <?php
                                $isEnrolled = isset($enrollments[$pelajaran->id]);
                                $isPending = $isEnrolled && $enrollments[$pelajaran->id]->status === 'pending';
                                $isApproved = $isEnrolled && $enrollments[$pelajaran->id]->status === 'approved';
                                ?>

                                @if (!$isEnrolled)
                                    <button class="btn-daftar"
                                        onclick="enrollSiswa({{ $pelajaran->id }})">Daftar</button>
                                @elseif ($isPending)
                                    <span class="text-warning">Menunggu persetujuan guru</span>
                                @elseif ($isApproved)
                                    <span class="text-success">Anda sudah terdaftar</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
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
